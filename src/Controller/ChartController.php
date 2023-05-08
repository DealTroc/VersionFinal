<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\LigneFacture;
use App\Entity\Facture;

class ChartController extends AbstractController
{
    /**
    * @Route("/chart", name="chart")
    */
    public function chart(ChartBuilderInterface $chartBuilder, EntityManagerInterface $entityManager)
    {
        $data = [];
        $notification_count=0;
        $message='facture payés';
        // Récupération des données des entités "ligne facture" et "facture"
        $lignesFacture = $entityManager->getRepository(LigneFacture::class)->findAll();
        $factures = $entityManager->getRepository(Facture::class)->findAll();

        // Construction des données pour la courbe
        foreach ($factures as $facture) {
            $dateFacturation = $facture->getDateFacturation();
            $revenu = 0;

            foreach ($lignesFacture as $ligneFacture) {
                if ($ligneFacture->getFacture() == $facture) {
                    $revenu += $ligneFacture->getRevenu();
                }
            }

            $data[] = [
                'x' => $dateFacturation->format('Y-m-d'),
                'y' => $revenu
            ];
        }

        // Construction du graphique
        $chart = $chartBuilder->createChart(Chart::TYPE_LINE);
        $chart->setData([
            'datasets' => [
                [   'label' => 'Revenu en (DT)',
                    'data' => $data
                ]
            ]
        ]);
        $chart->setOptions([
            'scales' => [
                'xAxes' => [
                    [
                        'type' => 'time',
                        'time' => [
                            'unit' => 'day',
                            'displayFormats' => [
                                'day' => 'MMM DD'
                            ]
                        ]
                    ]
                ]
            ]
        ]);

        
        // Affichage de la vue avec le graphique
        return $this->render('chart/index.html.twig', [
            'chart' => $chart ,'notification_count'=>$notification_count,'msg'=> $message
        ]);
    }
}
