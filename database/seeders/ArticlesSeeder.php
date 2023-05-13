<?php

namespace Database\Seeders;

use App\Models\article;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArticlesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $articles = [
            [
                'designationArticle' => "WHITE SPIRIT",
            ],
            [
                'designationArticle' => "CABLE D'ALIMENTATION POUR MICRO ORDINATEUR  " ,
            ],
            [
                'designationArticle' => 'CHIFFON ESSUYAGE ',
            ],
            [
                'designationArticle' => 'EPONGE VEGETALE "SPONDEX"',
            ],
            [
                'designationArticle' => 'BROSSE POUR TABLEAU ',
            ],
            [
                'designationArticle' => 'REGISTRE A 3 MAINS',
            ],
            [
                'designationArticle' => 'REGISTRE A 4 MAINS',
            ],
            [
                'designationArticle' => 'REGISTRE A 2 MAINS',
            ],
            [
                'designationArticle' => 'REGISTRE A 5 MAINS',
            ],
            [
                'designationArticle' => 'ENVLOPPE F22 (137X207 MM)',
            ],
            [
                'designationArticle' => 'ETIQUETTE POST-IT REF 609',
            ],
            [
                'designationArticle' => 'CHEMISE A RABAT EN CARTON DE 33X24 CM-300 GR',
            ],
            [
                'designationArticle' => 'MARQUEUR FEUTRE BLEU ',
            ],
            [
                'designationArticle' => 'MARQUEUR FEUTRE ROUGE ',
            ],
            [
                'designationArticle' => 'MARQUEUR FEUTRE NOIR ',
            ],
            [
                'designationArticle' => 'CLASSEUR CHRONO DE 32X38 CM',
            ],
            [
                'designationArticle' => 'REGISTRE COURRIER - DEBART',
            ],
            [
                'designationArticle' => 'REGISTRE COURRIER - ARRIVEE',
            ],
            [
                'designationArticle' => 'CALCULATRICE ',
            ],
            [
                'designationArticle' => 'SPIRALE ',
            ],
            [
                'designationArticle' => 'POCHETTE A ENTAILLE EN PLASTIQUE ',
            ],
            [
                'designationArticle' => "SCOTCH D'EMBALLAGE 50 MM ",
            ],
            [
                'designationArticle' => 'SERRE-FUEILLE DIFFERENTES COULEURS F-A4 D: 12 ',
            ],
            [
                'designationArticle' => 'SERRE-FUEILLE DIFFERENTES COULEURS F-A4 D: 14',
            ],
            [
                'designationArticle' => 'MARQUEUR FLUORECENT (EN PAQUET DE 6)',
            ],
            [
                'designationArticle' => 'TONER POUR IMPRIMANTE HP LASER JET 2035',
            ],
            [
                'designationArticle' => 'TONER POUR IMPRIMANTE LASER SAMSUNG ML 3470D',
            ],
            [
                'designationArticle' => 'TONER POUR PHOTOCOPIEUR KYOCERA KM 1635',
            ],
            [
                'designationArticle' => 'CLAVIER POUR MICRO ORDINATEUR (AZERTY)',
            ],
            [
                'designationArticle' => 'TONER POUR IMPRIMANTE LEXMARK OPTRA E 312',
            ],
            [
                'designationArticle' => 'TONER POUR IMPRIMANTE CANON LBP 6020',
            ],
            [
                'designationArticle' => 'TONER POUR IMPRIMANTE CANON LBP 800',
            ],
            [
                'designationArticle' => 'TONER POUR IMPRIMANTE HP 1300',
            ],
            [
                'designationArticle' => 'TONER POUR IMPRIMANTE SAMSUNG M4020ND',
            ],
            [
                'designationArticle' => 'TONER POUR IMPRIMANTE CANON LBP 6030',
            ],
            [
                'designationArticle' => 'CARTOUCHE MAGENTA POUR IMPRIMANTE HP 4650  REF: C9723',
            ],
            [
                'designationArticle' => 'TONER POUR IMPRIMANTE HP LASER 1320',
            ],
            [
                'designationArticle' => 'TONER KONICKA MINOLTA BIZHUB 363',
            ],
            [
                'designationArticle' => 'GRAVEUR DBD INTERNE ' ,
            ],
            [
                'designationArticle' => 'TONER HP LASER JET PRO NPF M26A ',
            ],
            [
                'designationArticle' => 'TONER PHOTOCOPIEUR KONICA MINOLTA 282 TN211',
            ],
            [
                'designationArticle' => 'TONER POUR IMPRIMANTE LASER SAMSUNG ML 2570',
            ],
            [
                'designationArticle' => 'TONER POUR IMPRIMANTE HP LASER JET M254 MAGENTA',
            ],
            [
                'designationArticle' => 'DEGALCEUR POUR BLANCHET',
            ],
            [
                'designationArticle' => 'DEGALCEUR POUR BLANCHET' ,
            ],
            [
                'designationArticle' => 'PRODUIT DE NETTOYAGE POUR PLAQUE OFFESET ',
            ],
            [
                'designationArticle' => 'BLANCHET COMPRESSIBLE FT : 605 X 752 MM EPAIS : 1,9 MM',
            ],
            [
                'designationArticle' => 'TYLO CORRECTEUR DE PLAQUE POSITIVE ',
            ],
            [
                'designationArticle' => 'ENCRE MEGENTA SUPERSET',
            ],
            [
                'designationArticle' => 'TONER HP LASER JET PRO 59A M 304A',
            ],
            [
                'designationArticle' => "ENCRE CYAN D'IMPRESSION PRIMAIRE POUR OFFESET ",
            ],
            [
                'designationArticle' => "ENCRE JAUNE D'IMPRESSION PRIMAIRE POUR OFFESET ",
            ],
            [
                'designationArticle' => "ENCRE NOIRE D'IMPRESSION PRIMAIRE POUR OFFESET ",
            ],
            [
                'designationArticle' => 'TONER POUR PHOTOCOPIEUR XEROX ',
            ],
            [
                'designationArticle' => 'TONER CANON 1215',
            ],
            [
                'designationArticle' => 'PAPIER CARONNE BRESTOL DE 100X65-24GR(RAME DE 125)',
            ],
            [
                'designationArticle' => 'CARTE BLANCHE DE 240 GR FT 65 X 100 EN RAME DE 125',
            ],
            [
                'designationArticle' => 'PAPIER BULLE DOUBLE A4 (RAMETTE DE 500)',
            ],
            [
                'designationArticle' => 'PAPIER BLANC PR IMPRIMA/PHOTOCOP.FT A4 80 GR(RAMETTE 500)',
            ],
            [
                'designationArticle' => 'PAPIER BLANC COUCHE BRILLANT 2 FACES 100X70-90 GR (R.DE 500)',
            ],
            [
                'designationArticle' => 'PAPIER BLANC OFFEST 64X45 72G (RAME DE 500)',
            ],
            [
                'designationArticle' => 'PAPIER BLANC SUP.DE 90 GR FT 65 X 100 EN RAME DE 500',
            ],
            [
                'designationArticle' => 'PAPIER COUCHE MAT FORMAT 67X102 CM RAME DE 500',
            ],
            [
                'designationArticle' => 'PAPIER BLANC PR PHOTOCOPIEUR FT A3 80 GRS (RAMETTE DE 500)',
            ],
            [
                'designationArticle' => 'PAPIER BLANC SUPERIEUR 90GR FORMAT A4 (EN RAMETTE DE 500)',
            ],
            [
                'designationArticle' => 'PANEAU DE TENSION MACH.RIM.669.00.2MD.06/REF:202558.0.00',
            ],
            [
                'designationArticle' => 'ALCOOL ISOPROPILIQUE ',
            ],
        ];

        DB::table('articles')->insert($articles);
    

    }
}
