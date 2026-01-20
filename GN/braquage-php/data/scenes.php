<?php
// Scenes extracted from the PDFs
// Structure: orga_text (instructions), character_contents (Alex, Charlie, Camille, Andréa), choices (navigation)

$scenesData = [
    // Acte 1, Scène 1
    [
        'id' => '1-1',
        'acte' => 1,
        'scene' => 1,
        'orga_text' => 'SCÈNE 1 - INSTRUCTIONS ORGA

[À extraire du livret Orga - Scène 1]

Les 4 personnages (Alex, Charlie, Camille, Andréa) sont présents. Chaque personnage a son propre livret avec le texte de cette scène.',
        'character_contents' => [
            [
                'character' => 'Alex',
                'introduction' => 'Alex a 12 ans et est avec ses 3 amis. Ils vont tous les 4 au bord de la mer, sur une des plages les moins fréquentées de Marseille, dans les calanques, pour faire des ricochets. C\'est à celui qui en fera le plus.

L\'accès à la plage est difficile, et Alex est surexcité d\'y être. Il a trouvé LE caillou idéal. Et puis il y a un rocher en hauteur d\'où ils pourraient sauter dans l\'eau...',
            ],
            // TODO: Extraire du livret Charlie - Scène 1
            // [
            //     'character' => 'Charlie',
            //     'introduction' => '...',
            // ],
            // TODO: Extraire du livret Camille - Scène 1
            // [
            //     'character' => 'Camille',
            //     'introduction' => '...',
            // ],
            [
                'character' => 'Andréa',
                'introduction' => 'Andréa a 12 ans et est avec ses 3 amis. Ils vont tous les 4 au bord de la mer, sur une des plages les moins fréquentées de Marseille, dans les calanques, pour faire des ricochets. C\'est à celui qui en fera le plus.

Andréa est heureux d\'être avec ses amis mais est également un peu nerveux, il n\'est pas sûr d\'avoir l\'autorisation de sa mère, et puis si l\'un d\'eux se blesse, comment prévenir les secours ? Et il est persuadé que l\'un de ses amis va proposer de se baigner, il n\'a même pas pris son maillot...',
            ],
        ],
        'choices' => [
            // À extraire du livret Orga - Scène 1
            // Si simple: ['id' => '1-1-1-3', 'description' => 'Continuer vers la scène suivante', 'target_scene_id' => '1-3'],
            // Si conditionnel: 
            // ['id' => '1-1-1-5', 'description' => 'Dans ce cas : passez à la scène 5', 'target_scene_id' => '1-5', 'condition' => 'cas_a'],
            // ['id' => '1-1-1-6', 'description' => 'Dans ce cas : passez à la scène 6', 'target_scene_id' => '1-6', 'condition' => 'cas_b'],
        ],
    ],
    
    // Acte 1, Scène 2 - Règlement de compte - Partie 1
    [
        'id' => '1-2',
        'acte' => 1,
        'scene' => 2,
        'orga_text' => 'SCÈNE 2 - Règlement de compte - Partie 1

## Personnages présents :
Andréa, Alex, Charlie, Camille.

## Introduction
Octobre 2007, dans le petit appartement d\'Andréa, dans une banlieue de Marseille. Alex et Andréa boivent tranquillement une bière, comme tous les jeudis soirs, comme un rituel instauré depuis quelques années maintenant. Assis tranquillement dans le canapé, devant un de ces films de série Z dont raffole Andréa, les deux amis discutent tranquillement. Lorsque la porte s\'ouvre avec fracas, laissant Camille dans l\'entrebâillement, une arme en main.

## Mise en scène
Deux chaises pour simuler le canapé. Alex et Andréa sur les chaises, un verre en main. Ils discutent un peu avant l\'entrée de Camille. Charlie arrive plus tard. Elle peut cette fois-ci arriver avant que Camille ne tire. L\'idéal est de commencer la scène quelques secondes avant l\'entrée de Camille. Il faut attendre une résolution.

Musique : Where is my mind - Placebo

## Information
Les joueurs ne doivent pas reproduire à l\'identique ce qu\'il s\'est passé dans la première version. Ils peuvent se servir de ce qu\'il s\'est passé pendant le jeu.

## Arborescence
Fin du jeu : générique complet "Where is my mind"',
        'character_contents' => [
            [
                'character' => 'Alex',
                'introduction' => 'Alex a 27 ans et a vécu sa vie à fond. Etre un enfant de riche, c\'est toujours plus facile pour vivre sans penser au lendemain. Surtout quand ce statut vous protège même de la justice.

Mais aujourd\'hui, sa vie va prendre fin.

Charlie, son amie d\'enfance, va bientôt entrer dans le petit appartement d\'Andréa et lui braquer une arme sur le front, prête à tirer. Alex Mérite-t-il de mourir de sa main ? Oui, il n\'y a aucun doute là-dessus. Alex est certainement responsable de tous les malheurs qui sont tombés sur le petit groupe d\'amis depuis qu\'ils sont gamins, mais une chose est sûre, il ne veut pas mourir !

Mais pour l\'instant, Alex est assis à côté d\'Andréa devant un film de série Z que son ami adore. Et Camille n\'est pas là. Pourtant sa présence aurait été utile pour désamorcer la situation qui va bientôt s\'aggraver.',
                'information' => 'Dans cette scène, Alex risque de mourir. Reste à savoir comment vous allez le jouer : implorant, acceptant son sort, défiant Charlie, tout est possible. Si bien sûr Charlie vous en laisse le temps. N\'oubliez pas qu\'une arme fait peur.',
            ],
            // TODO: Extraire Charlie, Camille, Andréa pour scène 2
        ],
        'choices' => [],
    ],
    
    // Acte 1, Scène 3
    [
        'id' => '1-3',
        'acte' => 1,
        'scene' => 3,
        'orga_text' => 'SCÈNE 3 - INSTRUCTIONS ORGA

[À extraire du livret Orga - Scène 3]

Cette scène prend fin immédiatement au début de la musique.',
        'character_contents' => [
            [
                'character' => 'Alex',
                'introduction' => 'Alex a 13 ans et a invité ses trois amis, Camille, Charlie et Andréa à profiter de la piscine de sa maison. Ses parents ne sont pas là, comme d\'habitude, mais peu importe, c\'est un grand maintenant. Alex n\'a plus besoin ni d\'eux, ni de la nourrice qui l\'a gardé toute son enfance.

Alex et Andréa sont sur le toit, prêts à sauter dans la piscine juste en dessous. Avec un peu d\'élan, on ne peut pas se louper.

Camille et Charlie ont déjà sauté et c\'est au tour d\'Andréa qui hésite, comme d\'habitude. Andréa a toujours peur de tout. Mais il n\'y a aucun risque si on saute correctement. Andréa doit sauter, pour prouver que ce n\'est pas une poule mouillée !',
                'information' => 'Vous allez tout faire pour inciter Andréa à sauter. Tout mais il doit prendre sa décision tout seul, le pousser ne serait pas drôle...',
            ],
            // TODO: Extraire Charlie, Camille, Andréa pour scène 3
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
        'orga_text' => 'SCÈNE 4 - INSTRUCTIONS ORGA

[À extraire du livret Orga - Scène 4]',
        'character_contents' => [
            [
                'character' => 'Alex',
                'introduction' => 'L\'oncle de Charlie a 43 ans. Voilà maintenant 6 ans qu\'il a la garde de Charlie, sa nièce. Sa sœur s\'est barrée, lui laissant la charge de sa fille. Il en veut beaucoup à sa sœur de lui avoir imposé ses choix, mais au moins, lui, sait prendre ses responsabilités. Ce n\'est pas toujours facile avec Charlie, mais au moins ils ont une passion commune pour la mécanique. Quand elle est les mains dans un moteur, elle est volontaire. Il apprécie beaucoup ces moments avec sa nièce, les seuls où ils arrivent à s\'entendre.

Elle est aujourd\'hui venue au garage où il travaille. Son patron n\'est pas là, et de toute façon cela ne le dérange pas que Charlie vienne de temps en temps. Les parents de son amie Camille ont un problème sur leur voiture. Il profite de ce moment pour lui montrer comment changer un pot d\'échappement, à Charlie mais aussi à son amie Camille. Rien de bien compliqué.',
            ],
            // TODO: Extraire Charlie, Camille, Andréa pour scène 4
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
        'orga_text' => 'SCÈNE 5 - INSTRUCTIONS ORGA

[À extraire du livret Orga - Scène 5]',
        'character_contents' => [
            [
                'character' => 'Alex',
                'introduction' => 'Alex a 13 ans et c\'est la rentrée des classes. Au collège, il est l\'élève le plus populaire. Son argent joue peut-être un peu, mais c\'est surtout son attitude désinvolte qui fait de lui une icône.

Tout le contraire d\'Andréa. Or aujourd\'hui, Andréa a des problèmes avec deux élèves de la classe d\'Alex. Deux brutes qui s\'en prennent à son argent de poche. Alex est du genre à régler les conflits tout en gardant l\'amitié des brutes en question. Il est même prêt à leur donner du fric si ils peuvent un peu lâcher Andréa.',
                'information' => 'Vous n\'intervenez pas tout de suite dans cette scène. Attendez le signal de l\'orga avant d\'entrer en scène.

Vous n\'en viendrez pas aux mains avec les deux autres élèves, ce n\'est pas votre genre. Vous êtes plutôt du genre à les amadouer, à les séduire, voir les acheter. N\'oubliez pas que nous sommes en 1993, parlez donc en francs.',
            ],
            // TODO: Extraire Charlie, Camille, Andréa pour scène 5
        ],
        'choices' => [],
    ],
    
    // Acte 1, Scène 6
    [
        'id' => '1-6',
        'acte' => 1,
        'scene' => 6,
        'orga_text' => 'SCÈNE 6 - INSTRUCTIONS ORGA

[À extraire du livret Orga - Scène 6]',
        'character_contents' => [
            [
                'character' => 'Alex',
                'introduction' => 'L\'oncle de Charlie a 43 ans. Voilà maintenant 6 ans qu\'il a la garde de Charlie, sa nièce. Sa sœur s\'est barrée, lui laissant la charge de sa fille. Il en veut beaucoup à sa sœur de lui avoir imposé ses choix, mais au moins, lui, sait prendre ses responsabilités. Ce n\'est pas toujours facile avec Charlie, mais au moins ils ont une passion commune pour la mécanique. Quand elle est les mains dans un moteur, elle est volontaire. Il apprécie beaucoup ces moments avec sa nièce, les seuls où ils arrivent à s\'entendre.',
            ],
            // TODO: Extraire Charlie, Camille, Andréa pour scène 6
        ],
        'choices' => [],
    ],
    
    // Scène 102 - Corps
    [
        'id' => '102',
        'acte' => 1,
        'scene' => 102,
        'orga_text' => 'SCÈNE 102 - INSTRUCTIONS ORGA

[À extraire du livret Orga - Scène 102]',
        'character_contents' => [
            [
                'character' => 'Alex',
                'introduction' => '',
                'information' => 'Vous êtes un corps sous un drap. Il suffit juste de ne pas bouger.',
            ],
            // TODO: Extraire Charlie, Camille, Andréa pour scène 102
        ],
        'choices' => [],
    ],
    
    // Scène 103
    [
        'id' => '103',
        'acte' => 1,
        'scene' => 103,
        'orga_text' => 'SCÈNE 103 - INSTRUCTIONS ORGA

[À extraire du livret Orga - Scène 103]',
        'character_contents' => [
            [
                'character' => 'Alex',
                'introduction' => '',
                'information' => 'Vous n\'intervenez pas dans cette scène.',
            ],
            // TODO: Extraire Charlie, Camille, Andréa pour scène 103
        ],
        'choices' => [],
    ],
    
    // Scène 104
    [
        'id' => '104',
        'acte' => 1,
        'scene' => 104,
        'orga_text' => 'SCÈNE 104 - INSTRUCTIONS ORGA

[À extraire du livret Orga - Scène 104]',
        'character_contents' => [
            [
                'character' => 'Alex',
                'introduction' => '',
                'information' => 'Vous n\'intervenez pas dans cette scène.',
            ],
            // TODO: Extraire Charlie, Camille, Andréa pour scène 104
        ],
        'choices' => [],
    ],
    
    // Scène 105
    [
        'id' => '105',
        'acte' => 1,
        'scene' => 105,
        'orga_text' => 'SCÈNE 105 - INSTRUCTIONS ORGA

[À extraire du livret Orga - Scène 105]

C\'est la dernière scène, servez vous de tout ce que vous avez vécu pour jouer cette scène.',
        'character_contents' => [
            [
                'character' => 'Alex',
                'introduction' => 'Alex a 27 ans et n\'est pas vraiment au meilleur de sa forme. Etre un enfant de riche n\'est pas toujours si facile que ça. Surtout quand ses parents n\'ont jamais vraiment eu un regard pour soi. Au moins il a ses amis. Mais ce soir, il va assister à la mort d\'Andréa.

Charlie, son amie d\'enfance, va bientôt entrer dans le petit appartement d\'Andréa et braquer une arme sur le front d\'Andréa, prête à tirer. Et Camille arrivera trop tard, dommage, elle aurait su quoi faire pour stopper Charlie.

Mais pour l\'instant, Alex est assis à côté d\'Andréa devant un film de série Z que son ami adore.',
                'information' => 'C\'est la dernière scène, servez vous de tout ce que vous avez vécu pour jouer cette scène. Si Alex est du genre déprimé, il n\'est pas pour autant suicidaire. Or, une arme fait peur !

Vous avez le temps du générique de fin pour sortir du jeu, un débrief est prévu juste après, éventuellement autour d\'un verre.',
            ],
            // TODO: Extraire Charlie, Camille, Andréa pour scène 105
        ],
        'choices' => [],
    ],
    
    // Scène 106
    [
        'id' => '106',
        'acte' => 1,
        'scene' => 106,
        'orga_text' => 'SCÈNE 106 - INSTRUCTIONS ORGA

[À extraire du livret Orga - Scène 106]

C\'est la dernière scène, servez vous de tout ce que vous avez vécu pour jouer cette scène.',
        'character_contents' => [
            [
                'character' => 'Alex',
                'introduction' => 'Alex a 27 ans et n\'est pas vraiment au meilleur de sa forme. Etre un enfant de riche n\'est pas toujours si facile que ça. Surtout quand ses parents n\'ont jamais vraiment eu un regard pour soi. Au moins il a ses amis. Mais ce soir, il va assister à la mort d\'Andréa.

Camille, son amie d\'enfance, va bientôt entrer dans le petit appartement d\'Andréa et braquer une arme sur le front d\'Andréa, prête à tirer. Et Charlie arrivera trop tard, dommage, elle aurait su quoi faire pour stopper Camille.

Mais pour l\'instant, Alex est assis à côté d\'Andréa devant un film de série Z que son ami adore.',
                'information' => 'C\'est la dernière scène, servez vous de tout ce que vous avez vécu pour jouer cette scène. Si Alex est du genre déprimé, il n\'est pas pour autant suicidaire. Or, une arme fait peur !

Vous avez le temps du générique de fin pour sortir du jeu, un débrief est prévu juste après, éventuellement autour d\'un verre.',
            ],
            // TODO: Extraire Charlie, Camille, Andréa pour scène 106
        ],
        'choices' => [],
    ],
    
    // Scène 107 - Règlement de compte - Partie 3
    [
        'id' => '107',
        'acte' => 1,
        'scene' => 107,
        'orga_text' => 'SCÈNE 107 - Règlement de compte - Partie 3

## Personnages présents :
Andréa, Alex, Charlie, Camille.

## Introduction
Octobre 2007, dans le petit appartement d\'Andréa, dans une banlieue de Marseille. Alex et Andréa boivent tranquillement une bière, comme tous les jeudis soirs, comme un rituel instauré depuis quelques années maintenant. Assis tranquillement dans le canapé, devant un de ces films de série Z dont raffole Andréa, les deux amis discutent tranquillement. Lorsque la porte s\'ouvre avec fracas, laissant Camille dans l\'entrebâillement, une arme en main.

## Mise en scène
Deux chaises pour simuler le canapé. Alex et Andréa sur les chaises, un verre en main. Ils discutent un peu avant l\'entrée de Camille. Charlie arrive plus tard. Elle peut cette fois-ci arriver avant que Camille ne tire. L\'idéal est de commencer la scène quelques secondes avant l\'entrée de Camille. Il faut attendre une résolution.

Musique : Where is my mind - Placebo

## Information
Les joueurs ne doivent pas reproduire à l\'identique ce qu\'il s\'est passé dans la première version. Ils peuvent se servir de ce qu\'il s\'est passé pendant le jeu.

## Arborescence
Fin du jeu : générique complet "Where is my mind"',
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
            // TODO: Extraire Charlie, Camille, Andréa pour scène 107
        ],
        'choices' => [],
    ],
    
    // Scène 108 - Règlement de compte - Partie 3
    [
        'id' => '108',
        'acte' => 1,
        'scene' => 108,
        'orga_text' => 'SCÈNE 108 - Règlement de compte - Partie 3

## Personnages présents :
Andréa, Alex, Charlie, Camille.

## Introduction
Octobre 2007, dans le petit appartement d\'Andréa, dans une banlieue de Marseille. Alex et Andréa boivent tranquillement une bière, comme tous les jeudis soirs, comme un rituel instauré depuis quelques années maintenant. Assis tranquillement dans le canapé, devant un de ces films de série Z dont raffole Andréa, les deux amis discutent tranquillement. Lorsqu\'on sonne à la porte.

## Mise en scène
Deux chaises pour simuler le canapé. Alex et Andréa sur les chaises, un verre en main. Ils discutent un peu avant l\'entrée de Camille. Peu de temps après, Charlie entre à son tour. L\'idéal est de commencer la scène quelques secondes avant l\'entrée de Camille. Il faut attendre une résolution.

Musique : Where is my mind - Placebo

## Information
Les joueurs ne doivent pas reproduire à l\'identique ce qu\'il s\'est passé dans la première version. Ils peuvent se servir de ce qu\'il s\'est passé pendant le jeu.

## Arborescence
Fin du jeu : générique complet "Where is my mind"',
        'character_contents' => [
            [
                'character' => 'Alex',
                'introduction' => 'Alex a 27 ans et n\'est pas vraiment au meilleur de sa forme. Etre un enfant de riche n\'est pas toujours si facile que ça. Surtout quand ses parents n\'ont jamais vraiment eu un regard pour soi. Au moins il a ses amis. Mais ce soir, il va assister à la mort de Camille.

Charlie, son amie d\'enfance, va bientôt entrer dans le petit appartement d\'Andréa et braquer une arme sur le front de Camille, arrivée plus tôt, prête à tirer.

Mais pour l\'instant, Alex est assis à côté d\'Andréa devant un film de série Z que son ami adore.',
                'information' => 'C\'est la dernière scène, servez vous de tout ce que vous avez vécu pour jouer cette scène. Si Alex est du genre déprimé, il n\'est pas pour autant suicidaire. Or, une arme fait peur !

Vous avez le temps du générique de fin pour sortir du jeu, un débrief est prévu juste après, éventuellement autour d\'un verre.',
            ],
            // TODO: Extraire Charlie, Camille, Andréa pour scène 108
        ],
        'choices' => [],
    ],
    
    // Scène 109 - Règlement de compte - Partie 3
    [
        'id' => '109',
        'acte' => 1,
        'scene' => 109,
        'orga_text' => 'SCÈNE 109 - Règlement de compte - Partie 3

## Personnages présents :
Andréa, Alex, Charlie, Camille.

## Introduction
Octobre 2007, dans le petit appartement d\'Andréa, dans une banlieue de Marseille. Alex et Andréa boivent tranquillement une bière, comme tous les jeudis soirs, comme un rituel instauré depuis quelques années maintenant. Assis tranquillement dans le canapé, devant un de ces films de série Z dont raffole Andréa, les deux amis discutent tranquillement. Lorsque la porte s\'ouvre avec fracas, laissant Charlie dans l\'entrebâillement, une arme en main.

## Mise en scène
Deux chaises pour simuler le canapé. Alex et Andréa sur les chaises, un verre en main. Ils discutent un peu avant l\'entrée de Charlie. Camille arrive plus tard. Elle peut cette fois-ci arriver avant que Charlie ne tire. L\'idéal est de commencer la scène quelques secondes avant l\'entrée de Charlie. Il faut attendre une résolution.

Musique : Where is my mind - Placebo

## Information
Les joueurs ne doivent pas reproduire à l\'identique ce qu\'il s\'est passé dans la première version. Ils peuvent se servir de ce qu\'il s\'est passé pendant le jeu.

## Arborescence
Fin du jeu : générique complet "Where is my mind"',
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
            // TODO: Extraire Charlie, Camille, Andréa pour scène 109
        ],
        'choices' => [],
    ],
];
