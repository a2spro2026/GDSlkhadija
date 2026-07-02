<?php

return [
    'actions' => [
        'voir' => ['label' => 'Voir', 'icon' => 'fa-eye'],
        'saisir' => ['label' => 'Saisir', 'icon' => 'fa-keyboard'],
        'modifier' => ['label' => 'Modifier', 'icon' => 'fa-pen-to-square'],
        'imprimer' => ['label' => 'Imprimer', 'icon' => 'fa-print'],
        'supprimer' => ['label' => 'Supprimer', 'icon' => 'fa-trash-can'],
    ],

    'groups' => [
        'Fournisseur' => [
            'fournisseur_fiche' => 'Fiche Fournisseur',
            'fournisseur_bons' => "Bon d'achats",
            'fournisseur_reglement' => 'Règlement',
            'fournisseur_balance' => 'Balance',
            'fournisseur_etat' => 'État Fournisseur',
        ],
        'Dépôt' => [
            'depot_iam' => 'Dépôt IAM',
            'depot_divers' => 'Dépôt Divers',
        ],
        'Gestion' => [
            'gestion_fiche_technicien' => 'Fiche Technicien',
            'gestion_etat_travaux' => 'État Travaux',
            'gestion_rapport_travaux' => 'Rapport Travaux',
            'gestion_rapport_technicien' => 'Rapport Technicien',
        ],
        'Opérations' => [
            'operations_stock' => 'Gestion Stock',
            'operations_taches' => 'Tâches',
            'operations_equipe' => 'Équipe',
        ],
        'Système' => [
            'systeme_configuration' => 'Configuration',
            'systeme_utilisateurs' => 'Gestion Utilisateurs',
        ],
    ],
];
