<?php
// Scenes extracted from the PDFs
$scenesData = [
    // Acte 1, Scène 1
    [
        'id' => '1-1',
        'acte' => 1,
        'scene' => 1,
        'orga_text' => 'INSTRUCTION ORGA SCÈNE 1-1

[À remplir avec le texte du livret Orga pour la scène 1]

Les 4 personnages (Alex, Charlie, Camille, Andréa) sont présents. Chaque personnage a son propre livret avec le texte de cette scène.',
        'character_contents' => [
            [
                'character' => 'Alex',
                'introduction' => 'Alex a 12 ans et est avec ses 3 amis. Ils vont tous les 4 au bord de la mer, sur une des plages les moins fréquentées de Marseille, dans les calanques, pour faire des ricochets. C\'est à celui qui en fera le plus.

L\'accès à la plage est difficile, et Alex est surexcité d\'y être. Il a trouvé LE caillou idéal. Et puis il y a un rocher en hauteur d\'où ils pourraient sauter dans l\'eau...',
            ],
            // TODO: Ajouter Charlie, Camille, Andréa pour cette scène
            // [
            //     'character' => 'Charlie',
            //     'introduction' => '...',
            // ],
            // [
            //     'character' => 'Camille',
            //     'introduction' => '...',
            // ],
            // [
            //     'character' => 'Andréa',
            //     'introduction' => '...',
            // ],
        ],
        'choices' => [
            ['id' => '1-1-1-3', 'description' => 'Continuer vers la scène suivante', 'target_scene_id' => '1-3'],
            // Exemple de choix conditionnel (à remplir selon le livret Orga) :
            // ['id' => '1-1-1-5', 'description' => 'Si condition A : passez à la scène 5', 'target_scene_id' => '1-5', 'condition' => 'condition_a'],
            // ['id' => '1-1-1-6', 'description' => 'Si condition B : passez à la scène 6', 'target_scene_id' => '1-6', 'condition' => 'condition_b'],
        ],
    ],
    
    // Acte 1, Scène 2
    [
        'id' => '1-2',
        'acte' => 1,
        'scene' => 2,
        'orga_text' => 'Texte principal du livret Orga pour la scène 1-2. Cette scène reviendra régulièrement dans le jeu. Elle est la scène initiale mais également finale, toutes les décisions prises durant le jeu ramènent à ce point.',
        'character_contents' => [
            [
                'character' => 'Alex',
                'introduction' => 'Alex a 27 ans et a vécu sa vie à fond. Etre un enfant de riche, c\'est toujours plus facile pour vivre sans penser au lendemain. Surtout quand ce statut vous protège même de la justice.

Mais aujourd\'hui, sa vie va prendre fin.

Charlie, son amie d\'enfance, va bientôt entrer dans le petit appartement d\'Andréa et lui braquer une arme sur le front, prête à tirer. Alex Mérite-t-il de mourir de sa main ? Oui, il n\'y a aucun doute là-dessus. Alex est certainement responsable de tous les malheurs qui sont tombés sur le petit groupe d\'amis depuis qu\'ils sont gamins, mais une chose est sûre, il ne veut pas mourir !

Mais pour l\'instant, Alex est assis à côté d\'Andréa devant un film de série Z que son ami adore. Et Camille n\'est pas là. Pourtant sa présence aurait été utile pour désamorcer la situation qui va bientôt s\'aggraver.',
                'information' => 'Dans cette scène, Alex risque de mourir. Reste à savoir comment vous allez le jouer : implorant, acceptant son sort, défiant Charlie, tout est possible. Si bien sûr Charlie vous en laisse le temps. N\'oubliez pas qu\'une arme fait peur.',
            ],
        ],
        'choices' => [],
    ],
    
    // Acte 1, Scène 3
    [
        'id' => '1-3',
        'acte' => 1,
        'scene' => 3,
        'orga_text' => 'Texte principal du livret Orga pour la scène 1-3. Cette scène prend fin immédiatement au début de la musique.',
        'character_contents' => [
            [
                'character' => 'Alex',
                'introduction' => 'Alex a 13 ans et a invité ses trois amis, Camille, Charlie et Andréa à profiter de la piscine de sa maison. Ses parents ne sont pas là, comme d\'habitude, mais peu importe, c\'est un grand maintenant. Alex n\'a plus besoin ni d\'eux, ni de la nourrice qui l\'a gardé toute son enfance.

Alex et Andréa sont sur le toit, prêts à sauter dans la piscine juste en dessous. Avec un peu d\'élan, on ne peut pas se louper.

Camille et Charlie ont déjà sauté et c\'est au tour d\'Andréa qui hésite, comme d\'habitude. Andréa a toujours peur de tout. Mais il n\'y a aucun risque si on saute correctement. Andréa doit sauter, pour prouver que ce n\'est pas une poule mouillée !',
                'information' => 'Vous allez tout faire pour inciter Andréa à sauter. Tout mais il doit prendre sa décision tout seul, le pousser ne serait pas drôle...',
            ],
        ],
        'choices' => [
            ['id' => '1-3-1-4', 'description' => 'Continuer vers la scène suivante', 'target_scene_id' => '1-4'],
        ],
    ],
    
    // Acte 1, Scène 4
    [
        'id' => '1-4',
        'acte' => 1,
        'scene' => 4,
        'orga_text' => 'Texte principal du livret Orga pour la scène 1-4. Instructions pour l\'organisation de cette scène.',
        'character_contents' => [
            [
                'character' => 'Oncle de Charlie',
                'introduction' => 'L\'oncle de Charlie a 43 ans. Voilà maintenant 6 ans qu\'il a la garde de Charlie, sa nièce. Sa sœur s\'est barrée, lui laissant la charge de sa fille. Il en veut beaucoup à sa sœur de lui avoir imposé ses choix, mais au moins, lui, sait prendre ses responsabilités. Ce n\'est pas toujours facile avec Charlie, mais au moins ils ont une passion commune pour la mécanique. Quand elle est les mains dans un moteur, elle est volontaire. Il apprécie beaucoup ces moments avec sa nièce, les seuls où ils arrivent à s\'entendre.

Elle est aujourd\'hui venue au garage où il travaille. Son patron n\'est pas là, et de toute façon cela ne le dérange pas que Charlie vienne de temps en temps. Les parents de son amie Camille ont un problème sur leur voiture. Il profite de ce moment pour lui montrer comment changer un pot d\'échappement, à Charlie mais aussi à son amie Camille. Rien de bien compliqué.',
            ],
        ],
        'choices' => [
            ['id' => '1-4-1-5', 'description' => 'Continuer vers la scène suivante', 'target_scene_id' => '1-5'],
        ],
    ],
    
    // Acte 1, Scène 5
    [
        'id' => '1-5',
        'acte' => 1,
        'scene' => 5,
        'orga_text' => 'Texte principal du livret Orga pour la scène 1-5. Instructions pour l\'organisation de cette scène.',
        'character_contents' => [
            [
                'character' => 'Alex',
                'introduction' => 'Alex a 13 ans et c\'est la rentrée des classes. Au collège, il est l\'élève le plus populaire. Son argent joue peut-être un peu, mais c\'est surtout son attitude désinvolte qui fait de lui une icône.

Tout le contraire d\'Andréa. Or aujourd\'hui, Andréa a des problèmes avec deux élèves de la classe d\'Alex. Deux brutes qui s\'en prennent à son argent de poche. Alex est du genre à régler les conflits tout en gardant l\'amitié des brutes en question. Il est même prêt à leur donner du fric si ils peuvent un peu lâcher Andréa.',
                'information' => 'Vous n\'intervenez pas tout de suite dans cette scène. Attendez le signal de l\'orga avant d\'entrer en scène.

Vous n\'en viendrez pas aux mains avec les deux autres élèves, ce n\'est pas votre genre. Vous êtes plutôt du genre à les amadouer, à les séduire, voir les acheter. N\'oubliez pas que nous sommes en 1993, parlez donc en francs.',
            ],
        ],
        'choices' => [],
    ],
    
    // Acte 1, Scène 6
    [
        'id' => '1-6',
        'acte' => 1,
        'scene' => 6,
        'orga_text' => 'Texte principal du livret Orga pour la scène 1-6. Instructions pour l\'organisation de cette scène.',
        'character_contents' => [
            [
                'character' => 'Oncle de Charlie',
                'introduction' => 'L\'oncle de Charlie a 43 ans. Voilà maintenant 6 ans qu\'il a la garde de Charlie, sa nièce. Sa sœur s\'est barrée, lui laissant la charge de sa fille. Il en veut beaucoup à sa sœur de lui avoir imposé ses choix, mais au moins, lui, sait prendre ses responsabilités. Ce n\'est pas toujours facile avec Charlie, mais au moins ils ont une passion commune pour la mécanique. Quand elle est les mains dans un moteur, elle est volontaire. Il apprécie beaucoup ces moments avec sa nièce, les seuls où ils arrivent à s\'entendre.',
            ],
        ],
        'choices' => [],
    ],
    
    // Scène 102 - Corps
    [
        'id' => '102',
        'acte' => 1,
        'scene' => 102,
        'orga_text' => 'Texte principal du livret Orga pour la scène 102. Instructions pour l\'organisation de cette scène.',
        'character_contents' => [
            [
                'character' => 'Corps',
                'introduction' => '',
                'information' => 'Vous êtes un corps sous un drap. Il suffit juste de ne pas bouger.',
            ],
        ],
        'choices' => [],
    ],
    
    // Scène 103
    [
        'id' => '103',
        'acte' => 1,
        'scene' => 103,
        'orga_text' => 'Texte principal du livret Orga pour la scène 103. Instructions pour l\'organisation de cette scène.',
        'character_contents' => [],
        'choices' => [],
    ],
    
    // Scène 104
    [
        'id' => '104',
        'acte' => 1,
        'scene' => 104,
        'orga_text' => 'Texte principal du livret Orga pour la scène 104. Instructions pour l\'organisation de cette scène.',
        'character_contents' => [],
        'choices' => [],
    ],
    
    // Scène 105
    [
        'id' => '105',
        'acte' => 1,
        'scene' => 105,
        'orga_text' => 'Texte principal du livret Orga pour la scène 105. C\'est la dernière scène, servez vous de tout ce que vous avez vécu pour jouer cette scène.',
        'character_contents' => [
            [
                'character' => 'Alex',
                'introduction' => 'Alex a 27 ans et n\'est pas vraiment au meilleur de sa forme. Etre un enfant de riche n\'est pas toujours si facile que ça. Surtout quand ses parents n\'ont jamais vraiment eu un regard pour soi. Au moins il a ses amis. Mais ce soir, il va assister à la mort d\'Andréa.

Charlie, son amie d\'enfance, va bientôt entrer dans le petit appartement d\'Andréa et braquer une arme sur le front d\'Andréa, prête à tirer. Et Camille arrivera trop tard, dommage, elle aurait su quoi faire pour stopper Charlie.

Mais pour l\'instant, Alex est assis à côté d\'Andréa devant un film de série Z que son ami adore.',
                'information' => 'C\'est la dernière scène, servez vous de tout ce que vous avez vécu pour jouer cette scène. Si Alex est du genre déprimé, il n\'est pas pour autant suicidaire. Or, une arme fait peur !

Vous avez le temps du générique de fin pour sortir du jeu, un débrief est prévu juste après, éventuellement autour d\'un verre.',
            ],
        ],
        'choices' => [],
    ],
    
    // Scène 106
    [
        'id' => '106',
        'acte' => 1,
        'scene' => 106,
        'orga_text' => 'Texte principal du livret Orga pour la scène 106. C\'est la dernière scène, servez vous de tout ce que vous avez vécu pour jouer cette scène.',
        'character_contents' => [
            [
                'character' => 'Alex',
                'introduction' => 'Alex a 27 ans et n\'est pas vraiment au meilleur de sa forme. Etre un enfant de riche n\'est pas toujours si facile que ça. Surtout quand ses parents n\'ont jamais vraiment eu un regard pour soi. Au moins il a ses amis. Mais ce soir, il va assister à la mort d\'Andréa.

Camille, son amie d\'enfance, va bientôt entrer dans le petit appartement d\'Andréa et braquer une arme sur le front d\'Andréa, prête à tirer. Et Charlie arrivera trop tard, dommage, elle aurait su quoi faire pour stopper Camille.

Mais pour l\'instant, Alex est assis à côté d\'Andréa devant un film de série Z que son ami adore.',
                'information' => 'C\'est la dernière scène, servez vous de tout ce que vous avez vécu pour jouer cette scène. Si Alex est du genre déprimé, il n\'est pas pour autant suicidaire. Or, une arme fait peur !

Vous avez le temps du générique de fin pour sortir du jeu, un débrief est prévu juste après, éventuellement autour d\'un verre.',
            ],
        ],
        'choices' => [],
    ],
    
    // Scène 107
    [
        'id' => '107',
        'acte' => 1,
        'scene' => 107,
        'orga_text' => 'Texte principal du livret Orga pour la scène 107. C\'est la dernière scène, servez vous de tout ce que vous avez vécu pour jouer cette scène.',
        'character_contents' => [
            [
                'character' => 'Alex',
                'introduction' => 'Alex a 27 ans et n\'est pas vraiment au meilleur de sa forme. Etre un enfant de riche n\'est pas toujours si facile que ça. Surtout quand ses parents n\'ont jamais vraiment eu un regard pour soi.

Et ce soir, sa vie va prendre fin.

Camille, son amie d\'enfance, va bientôt entrer dans le petit appartement d\'Andréa et lui braquer une arme sur le front, prête à tirer, et Charlie arrivera trop tard. Alex Mérite-t-il de mourir de sa main ? Oui, il n\'y a aucun doute là-dessus. N\'est-il pas responsable de la mort d\'Ange ? Il était plus vieux, il aurait dû l\'empêcher de s\'enfoncer dans la drogue, plutôt que de l\'entraîner. Mais comment pouvait-il s\'en rendre compte alors qu\'il était lui-même complètement plongé dedans.

Une chose est sûre, malgré son comportement dépressif et sa vie de merde, Alex ne veut pas mourir...

Pour l\'instant, Alex est assis à côté d\'Andréa devant un film de série Z que son ami adore.',
                'information' => 'C\'est la dernière scène, servez vous de tout ce que vous avez vécu pour jouer cette scène. Si Alex est du genre déprimé, il n\'est pas pour autant suicidaire. Or, une arme fait peur !

Vous avez le temps du générique de fin pour sortir du jeu, un débrief est prévu juste après, éventuellement autour d\'un verre.',
            ],
        ],
        'choices' => [],
    ],
    
    // Scène 108
    [
        'id' => '108',
        'acte' => 1,
        'scene' => 108,
        'orga_text' => 'Texte principal du livret Orga pour la scène 108. C\'est la dernière scène, servez vous de tout ce que vous avez vécu pour jouer cette scène.',
        'character_contents' => [
            [
                'character' => 'Alex',
                'introduction' => 'Alex a 27 ans et n\'est pas vraiment au meilleur de sa forme. Etre un enfant de riche n\'est pas toujours si facile que ça. Surtout quand ses parents n\'ont jamais vraiment eu un regard pour soi. Au moins il a ses amis. Mais ce soir, il va assister à la mort de Camille.

Charlie, son amie d\'enfance, va bientôt entrer dans le petit appartement d\'Andréa et braquer une arme sur le front de Camille, arrivée plus tôt, prête à tirer.

Mais pour l\'instant, Alex est assis à côté d\'Andréa devant un film de série Z que son ami adore.',
                'information' => 'C\'est la dernière scène, servez vous de tout ce que vous avez vécu pour jouer cette scène. Si Alex est du genre déprimé, il n\'est pas pour autant suicidaire. Or, une arme fait peur !

Vous avez le temps du générique de fin pour sortir du jeu, un débrief est prévu juste après, éventuellement autour d\'un verre.',
            ],
        ],
        'choices' => [],
    ],
    
    // Scène 109
    [
        'id' => '109',
        'acte' => 1,
        'scene' => 109,
        'orga_text' => 'Texte principal du livret Orga pour la scène 109. C\'est la dernière scène, servez vous de tout ce que vous avez vécu pour jouer cette scène.',
        'character_contents' => [
            [
                'character' => 'Alex',
                'introduction' => 'Alex a 27 ans et a vécu sa vie à fond. Etre un enfant de riche n\'est pas toujours si facile que ça. Surtout quand ses parents n\'ont jamais vraiment eu un regard pour soi.

Et ce soir, sa vie va prendre fin.

Charlie, son amie d\'enfance, va bientôt entrer dans le petit appartement d\'Andréa et lui braquer une arme sur le front, prête à tirer, et Camille arrivera trop tard. Alex Mérite-t-il de mourir de sa main ? Oui, il n\'y a aucun doute là-dessus. N\'est-il pas responsable de la mort de Sacha ?

Une chose est sûre, malgré son comportement dépressif et sa vie de merde, Alex ne veut pas mourir...

Pour l\'instant, Alex est assis à côté d\'Andréa devant un film de série Z que son ami adore.',
                'information' => 'C\'est la dernière scène, servez vous de tout ce que vous avez vécu pour jouer cette scène. Si Alex est du genre déprimé, il n\'est pas pour autant suicidaire. Or, une arme fait peur !

Vous avez le temps du générique de fin pour sortir du jeu, un débrief est prévu juste après, éventuellement autour d\'un verre.',
            ],
        ],
        'choices' => [],
    ],
];

