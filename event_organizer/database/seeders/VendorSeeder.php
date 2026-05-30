<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VendorSeeder extends Seeder
{
    public function run(): void
    {
        $vendors = [
            [
                'id' => 1,
                'name' => 'Fenix EO',
                'address' => 'Ruko Royal Dharmahusada CC, Surabaya, Indonesia',
                'instagram' => '@fenixorganizer',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 2,
                'name' => 'Boncafe Raya Gubeng',
                'address' => 'Jl. Raya Gubeng No.46, Gubeng, Kec. Gubeng, Surabaya, Jawa Timur 60281',
                'instagram' => '@boncafesteak',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 3,
                'name' => 'The Cafe (Java Paragon)',
                'address' => 'Jl. Mayjen Sungkono No.101-103, Dukuh Pakis, Kec. Dukuhpakis, Surabaya, Jawa Timur 60224',
                'instagram' => '@javaparagonhotel',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 4,
                'name' => 'New Royal',
                'address' => 'Manyar Kertoarjo No. 39-41, Surabaya, Indonesia 60286',
                'instagram' => '@newroyalrestaurant.sby',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 5,
                'name' => 'Laviere Lounge (Morazen)',
                'address' => 'Kayoon St No.4 - 10, Embong Kaliasin, Genteng, Surabaya, East Java 60271',
                'instagram' => '@lavieresurabaya',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 6,
                'name' => 'Confera',
                'address' => 'Gwalk, Citraland, Jl. Puri Widya Kencana LL-03, Surabaya, Jawa Timur, Indonesia, 60216',
                'instagram' => '@confera_cafe_resto',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 7,
                'name' => 'Tamo Venue',
                'address' => 'itraland Surabaya, Jl. Royal Park TL1/7-8, Lidah Kulon, Kec. Lakarsantri, Surabaya, Jawa Timur 60213',
                'instagram' => '@tamo.venue',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 8,
                'name' => 'Palatier Resto (Morazen)',
                'address' => 'Kayoon St No.4 - 10, Embong Kaliasin, Genteng, Surabaya, East Java 60271',
                'instagram' => '@palatiersurabaya',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 9,
                'name' => 'Lime Resto (Four Points, Tunjungan)',
                'address' => 'Jl. Embong Malang No.25 -31, Kedungdoro, Tegalsari, Surabaya, East Java 60261',
                'instagram' => '@limeatfourpoints',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 10,
                'name' => 'Casa Milieu',
                'address' => 'Citraland Waterfront WP 1 No.16, Surabaya, Jawa Timur 60219',
                'instagram' => '@timesatmilieu',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 11,
                'name' => 'The Socialite',
                'address' => 'Jl. Indragiri No.36, Darmo, Kec. Wonokromo, Surabaya, Jawa Timur 60241',
                'instagram' => '@thesocialite.id',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 12,
                'name' => 'Zhang Palace',
                'address' => 'Jl. Raya Lontar No.127, Babatan, Kec. Wiyung, Surabaya, Jawa Timur 60216',
                'instagram' => '@zhangpalace',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 13,
                'name' => 'Pavilion (JW Marriott Surabaya)',
                'address' => 'Jl. Embong Malang No.85-89, Kedungdoro, Kec. Tegalsari, Surabaya, Jawa Timur 60261',
                'instagram' => '@pavilionatjw',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 14,
                'name' => 'XO Palace',
                'address' => 'Jl. Raya Kupang Indah No.15, Dukuh Kupang, Kec. Dukuhpakis, Surabaya, Jawa Timur 60225',
                'instagram' => '@pavilionatjw',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 15,
                'name' => 'Santika Premiere Ballroom',
                'address' => 'Jl. Raya Gubeng No.54, Gubeng, Kec. Gubeng, Surabaya, Jawa Timur 60281',
                'instagram' => '@santikapremieregubeng',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 16,
                'name' => 'Morazen Ballroom',
                'address' => 'Kayoon St No.4 - 10, Embong Kaliasin, Genteng, Surabaya, East Java 60271',
                'instagram' => '@morazensurabaya',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 17,
                'name' => 'Depot Bu Tin',
                'address' => 'Jl. Dharmahusada Utara No.34, Gubeng, Surabaya, Jawa Timur 60285',
                'instagram' => '@butin_surabaya',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 18,
                'name' => 'Possa Wedding',
                'address' => 'Surabaya',
                'instagram' => '@possawedding',
                'logo' => 'vendor_logos/logo-possa.jpg',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 19,
                'name' => 'Kama',
                'address' => 'Surabaya',
                'instagram' => '@kamaphotographyofficial',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 20,
                'name' => 'Lovi Story',
                'address' => 'Surabaya',
                'instagram' => '@lovistory.weddings',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 21,
                'name' => 'Caleos Photography',
                'address' => 'Surabaya',
                'instagram' => '@caleosphotography',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 22,
                'name' => 'Revire',
                'address' => 'Surabaya',
                'instagram' => '@revirefortwo',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 23,
                'name' => 'Dave Galery',
                'address' => 'Surabaya',
                'instagram' => '@davegalery',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 24,
                'name' => 'Sinyo Photography',
                'address' => 'Surabaya',
                'instagram' => '@sinyophoto',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 25,
                'name' => 'PJ',
                'address' => 'Surabaya',
                'instagram' => '@pjphotographyid',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 26,
                'name' => 'Daily Photoworks',
                'address' => 'Surabaya',
                'instagram' => '@dailyphotoworks',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 27,
                'name' => 'Muel Photography',
                'address' => 'Surabaya',
                'instagram' => '@muel_photography',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 28,
                'name' => 'Diverso Fotografia',
                'address' => 'Surabaya',
                'instagram' => '@diverso_fotografia',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 29,
                'name' => 'Best Decoration',
                'address' => 'Surabaya',
                'instagram' => '@bestdecorsurabaya',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 30,
                'name' => 'M2 Decoration',
                'address' => 'Surabaya',
                'instagram' => '@m2_decor',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 31,
                'name' => 'Butterfly Decoration',
                'address' => 'Surabaya',
                'instagram' => '@butterfly_decor',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 32,
                'name' => 'Steve Decoration',
                'address' => 'Surabaya',
                'instagram' => '@steve_decor',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 33,
                'name' => 'Elora Decoration',
                'address' => 'Surabaya',
                'instagram' => '@elora.decor',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 34,
                'name' => 'Grace Wang Bridal',
                'address' => 'Jl. Taman Mansion Lakarsantri I, Jeruk, Kec. Lakarsantri, Surabaya, Jawa Timur 60212',
                'instagram' => '@gracewangbridal',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 35,
                'name' => 'JD Bridal',
                'address' => 'Jl. Menur Pumpungan No.22, Menur Pumpungan, Kec. Sukolilo, Surabaya, Jawa Timur 60118',
                'instagram' => '@jd.bridaland_st',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 36,
                'name' => 'Vina Wijayanti Bridal',
                'address' => 'Ruko Bukit Darmo Golf R16, Surabaya, Jawa Timur 60226',
                'instagram' => '@vinawijayantibridal',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 37,
                'name' => 'House of Lea',
                'address' => 'Darmo Hill, Jl. Pakis Bukit Akasia Blok N 15-17, Surabaya, Jawa Timur 60225',
                'instagram' => '@house_of_lea',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 38,
                'name' => 'Ovan Putri',
                'address' => 'Kertajaya Indah Timur XII Kertajaya No.36-38, Gebang Putih, Kec. Sukolilo, Surabaya, Jawa Timur 60132',
                'instagram' => '@ovanputri',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 39,
                'name' => 'Reine Atelier',
                'address' => 'Jl. Wonorejo Permai Selatan V, Wonorejo, Kec. Rungkut, Surabaya, Jawa Timur 60296',
                'instagram' => '@reineatelier',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 40,
                'name' => 'Sanjin Suits',
                'address' => 'Citraland, Balerina Raya Road Villa Sentra Selatan Lakarsantri No.A1-3A, Kec. Sambikerep, Surabaya, Jawa Timur 60217',
                'instagram' => '@sanjinsuits',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 41,
                'name' => 'Bie Hin Tailor',
                'address' => 'Jl. Pahlawan No.79, Alun-alun Contong, Kec. Bubutan, Surabaya, Jawa Timur 60174',
                'instagram' => '@biehintailor',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 42,
                'name' => 'Michael Christian',
                'address' => 'Surabaya',
                'instagram' => '@michaelchriistian',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 43,
                'name' => 'Juan Filbert',
                'address' => 'Surabaya',
                'instagram' => '@juanfilbertf',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 44,
                'name' => 'Fransiskus Nduti',
                'address' => 'Malang',
                'instagram' => '@fransiskusndut',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 45,
                'name' => 'Charly Hong',
                'address' => 'Surabaya',
                'instagram' => '@charly_hongdiyanto',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 46,
                'name' => 'Jeff Liu',
                'address' => 'Surabaya',
                'instagram' => '@jeffliu___',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 47,
                'name' => 'David Funata',
                'address' => 'Surabaya',
                'instagram' => '@davidfunata',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 48,
                'name' => 'Piniela Sutandi',
                'address' => 'Surabaya',
                'instagram' => '@piniela_',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 49,
                'name' => 'Freshtunes',
                'address' => 'Surabaya',
                'instagram' => '@freshtunesid',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 50,
                'name' => 'Glimpse Music',
                'address' => 'Surabaya',
                'instagram' => '@glimpse.music',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 51,
                'name' => 'Sweet Acoustic Band',
                'address' => 'Surabaya',
                'instagram' => '@sweetcousticband',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 52,
                'name' => 'Groovy Cake',
                'address' => 'Surabaya',
                'instagram' => '@groovycakesurabaya',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 53,
                'name' => 'Its cake',
                'address' => 'Surabaya',
                'instagram' => '@itscakesurabaya',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 54,
                'name' => 'Clarity Production',
                'address' => 'Surabaya',
                'instagram' => '@clarity.production',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 55,
                'name' => 'Tini Booth',
                'address' => 'Surabaya',
                'instagram' => '@tinibooth',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 56,
                'name' => 'Athalia Usherettes',
                'address' => 'Surabaya',
                'instagram' => '@athalia.usherettes',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 57,
                'name' => 'Pribadi',
                'address' => '-',
                'instagram' => '-',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 58,
                'name' => 'Novotel Samator East Surabaya Hotel',
                'address' => 'Jl. Raya Kedung Baruk No.26-28, Kedung Baruk, Kec. Rungkut, Surabaya, Jawa Timur 60298',
                'instagram' => '@novotel_samator',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 59,
                'name' => 'Depplight',
                'address' => 'Surabaya',
                'instagram' => '@depp.light',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 60,
                'name' => 'Bethany Church Nginden',
                'address' => 'Jalan Nginden Intan Timur I No.29, Nginden Jangkungan, Kec. Sukolilo, Surabaya, Jawa Timur 60118',
                'instagram' => '@successfulbethanyfamilies',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 61,
                'name' => 'Evergreen Cake',
                'address' => 'Surabaya',
                'instagram' => '@evergreencakesurabaya',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 62,
                'name' => 'Glow Effect',
                'address' => 'Surabaya',
                'instagram' => '@gloweffect',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 63,
                'name' => 'Manifest',
                'address' => 'Surabaya',
                'instagram' => '@manifestmusic_entertainment',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 64,
                'name' => 'Ann Pagar Ayu',
                'address' => 'Surabaya',
                'instagram' => '@ann_pagarayu',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 65,
                'name' => 'Trikarta',
                'address' => 'Surabaya',
                'instagram' => '@trikarta',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 66,
                'name' => 'Sempurna Invitations',
                'address' => 'Grand City Surabaya, Jl. Wali kota Mustajab No.1, Ketabang, Kec. Genteng, Surabaya, Jawa Timur 60272',
                'instagram' => '@sempurnainvitations',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 67,
                'name' => 'Calief Invitation',
                'address' => 'Surabaya',
                'instagram' => '-',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 68,
                'name' => 'Si Manten',
                'address' => 'Surabaya',
                'instagram' => '@simanten',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 69,
                'name' => 'Stefany Hyperstage Visual',
                'address' => 'Surabaya',
                'instagram' => '-',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 70,
                'name' => 'Nasi Campur Tambak Bayan',
                'address' => 'Jl. Tambak Bayan No.21, Alun-alun Contong, Kec. Bubutan, Surabaya, Jawa Timur 60174',
                'instagram' => '-',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 71,
                'name' => 'Four Clover',
                'address' => 'Surabaya',
                'instagram' => 'fourclover.florist',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 72,
                'name' => 'Retro Magis',
                'address' => 'Surabaya',
                'instagram' => '@retromagis',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 73,
                'name' => 'Han Palace',
                'address' => 'Surabaya',
                'instagram' => '@hanpalace.surabaya',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 74,
                'name' => 'Xiang Fu Hai',
                'address' => 'Surabaya',
                'instagram' => '@xiangfuhaicuisine',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 75,
                'name' => 'Double Tree',
                'address' => 'Surabaya',
                'instagram' => '@doubletreebyhiltonsurabaya',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 76,
                'name' => 'Savore',
                'address' => 'Jl. Mayjen HR. Muhammad No.31, Surabaya, Indonesia 60189',
                'instagram' => '@vasahotel',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 77,
                'name' => 'Vasa Hotel',
                'address' => 'Jl. Mayjen HR. Muhammad No.31, Surabaya, Indonesia 60189',
                'instagram' => '@vasahotel',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        $formattedVendors = [];
        foreach ($vendors as $v) {
            $formattedVendors[] = [
                'id' => $v['id'],
                'name' => $v['name'],
                'address' => $v['address'],
                'instagram' => $v['instagram'],
                'logo' => $v['logo'] ?? null,
                'created_at' => $v['created_at'],
                'updated_at' => $v['updated_at'],
            ];
        }

        foreach (array_chunk($formattedVendors, 50) as $chunk) {
            DB::table('vendors')->insert($chunk);
        }

        $categoryVendor = [
            //Fenix EO
            ['vendor_id' => 1, 'category_id' => 43], //Baloon & Dove
            ['vendor_id' => 1, 'category_id' => 44], //Flower Shower
            ['vendor_id' => 1, 'category_id' => 45], //Misua & Angco Tea
            ['vendor_id' => 1, 'category_id' => 20], //Meal Crew
            ['vendor_id' => 1, 'category_id' => 12], //Tie
            ['vendor_id' => 1, 'category_id' => 18], //Wedding Car
            ['vendor_id' => 1, 'category_id' => 25], //Genset
            ['vendor_id' => 1, 'category_id' => 27], //LCD
            ['vendor_id' => 1, 'category_id' => 28], //MC
            ['vendor_id' => 1, 'category_id' => 40], //Digital Guest Book

            //Boncafe Raya Gubeng
            ['vendor_id' => 2, 'category_id' => 22], //Venue

            //The Cafe (Java Paragon)
            ['vendor_id' => 3, 'category_id' => 22], //Venue

            //New Royal
            ['vendor_id' => 4, 'category_id' => 22], //Venue

            //Previere Lounge (Morazen)
            ['vendor_id' => 5, 'category_id' => 22], //Venue

            //Confera
            ['vendor_id' => 6, 'category_id' => 22], //Venue

            //Tamo Venue
            ['vendor_id' => 7, 'category_id' => 22], //Venue

            //Palatier Resto (Morazen)
            ['vendor_id' => 8, 'category_id' => 22], //Venue

            //Lime Resto (Four Points, Tunjungan)
            ['vendor_id' => 9, 'category_id' => 22], //Venue

            //Casa Milieu
            ['vendor_id' => 10, 'category_id' => 22], //Venue

            //The Socialite
            ['vendor_id' => 11, 'category_id' => 22], //Venue

            //Zhang Palace
            ['vendor_id' => 12, 'category_id' => 22], //Venue

            //Pavilion (JW Marriott Surabaya)
            ['vendor_id' => 13, 'category_id' => 22], //Venue

            //XO Palace
            ['vendor_id' => 14, 'category_id' => 22], //Venue
            ['vendor_id' => 14, 'category_id' => 23], //Sound
            ['vendor_id' => 14, 'category_id' => 26], //LED
            ['vendor_id' => 14, 'category_id' => 27], //LCD
            ['vendor_id' => 14, 'category_id' => 46], //Pyramid Fountain & Toast

            //Santika Premiere Ballroom
            ['vendor_id' => 15, 'category_id' => 22], //Venue

            //Morazen Ballroom
            ['vendor_id' => 16, 'category_id' => 22], //Venue

            //Depot Bu Tin
            ['vendor_id' => 17, 'category_id' => 20], //Meal Crew

            //Possa Wedding
            ['vendor_id' => 18, 'category_id' => 6], //Photographer
            ['vendor_id' => 18, 'category_id' => 5], //Videographer

            //Kama
            ['vendor_id' => 19, 'category_id' => 6], //Photographer
            ['vendor_id' => 19, 'category_id' => 5], //Videographer

            //Lovi Story
            ['vendor_id' => 20, 'category_id' => 6], //Photographer
            ['vendor_id' => 20, 'category_id' => 5], //Videographer

            //Caleos Photography
            ['vendor_id' => 21, 'category_id' => 6], //Photographer
            ['vendor_id' => 21, 'category_id' => 5], //Videographer

            //Revire
            ['vendor_id' => 22, 'category_id' => 6], //Photographer
            ['vendor_id' => 22, 'category_id' => 5], //Videographer

            //Dave Galery
            ['vendor_id' => 23, 'category_id' => 6], //Photographer
            ['vendor_id' => 23, 'category_id' => 5], //Videographer

            //Sinyo Photography
            ['vendor_id' => 24, 'category_id' => 6], //Photographer
            ['vendor_id' => 24, 'category_id' => 5], //Videographer

            //PJ
            ['vendor_id' => 25, 'category_id' => 6], //Photographer
            ['vendor_id' => 25, 'category_id' => 5], //Videographer

            //Daily Photoworks
            ['vendor_id' => 26, 'category_id' => 6], //Photographer
            ['vendor_id' => 26, 'category_id' => 5], //Videographer

            //Muel Photography
            ['vendor_id' => 27, 'category_id' => 6], //Photographer
            ['vendor_id' => 27, 'category_id' => 5], //Videographer

            //Diverso Fotografia
            ['vendor_id' => 28, 'category_id' => 6], //Photographer
            ['vendor_id' => 28, 'category_id' => 5], //Videographer

            //Best Decoration
            ['vendor_id' => 29, 'category_id' => 13], //Room Decoration
            ['vendor_id' => 29, 'category_id' => 14], //Hand Bouquet
            ['vendor_id' => 29, 'category_id' => 15], //Corsage
            ['vendor_id' => 29, 'category_id' => 30], //Venue Decoration
            ['vendor_id' => 29, 'category_id' => 31], //Church Decoration

            //M2 Decoration
            ['vendor_id' => 30, 'category_id' => 13], //Room Decoration
            ['vendor_id' => 30, 'category_id' => 14], //Hand Bouquet
            ['vendor_id' => 30, 'category_id' => 15], //Corsage
            ['vendor_id' => 30, 'category_id' => 30], //Venue Decoration
            ['vendor_id' => 30, 'category_id' => 31], //Church Decoration

            //Butterfly Decoration
            ['vendor_id' => 31, 'category_id' => 13], //Room Decoration
            ['vendor_id' => 31, 'category_id' => 14], //Hand Bouquet
            ['vendor_id' => 31, 'category_id' => 15], //Corsage
            ['vendor_id' => 31, 'category_id' => 30], //Venue Decoration
            ['vendor_id' => 31, 'category_id' => 31], //Church Decoration

            //Steve Decoration
            ['vendor_id' => 32, 'category_id' => 13], //Room Decoration
            ['vendor_id' => 32, 'category_id' => 14], //Hand Bouquet
            ['vendor_id' => 32, 'category_id' => 15], //Corsage
            ['vendor_id' => 32, 'category_id' => 30], //Venue Decoration
            ['vendor_id' => 32, 'category_id' => 31], //Church Decoration

            //Elora Decoration
            ['vendor_id' => 33, 'category_id' => 13], //Room Decoration
            ['vendor_id' => 33, 'category_id' => 14], //Hand Bouquet
            ['vendor_id' => 33, 'category_id' => 15], //Corsage
            ['vendor_id' => 33, 'category_id' => 30], //Venue Decoration
            ['vendor_id' => 33, 'category_id' => 31], //Church Decoration

            //Grace Wang Bridal
            ['vendor_id' => 34, 'category_id' => 2], //MUA
            ['vendor_id' => 34, 'category_id' => 3], //Gown
            ['vendor_id' => 34, 'category_id' => 9], //Hair Styling
            ['vendor_id' => 34, 'category_id' => 10], //Headpiece
            ['vendor_id' => 34, 'category_id' => 11], //Robe & Veil

            //JD Bridal
            ['vendor_id' => 35, 'category_id' => 2], //MUA
            ['vendor_id' => 35, 'category_id' => 3], //Gown
            ['vendor_id' => 35, 'category_id' => 9], //Hair Styling
            ['vendor_id' => 35, 'category_id' => 10], //Headpiece
            ['vendor_id' => 35, 'category_id' => 11], //Robe & Veil

            //Vina Wijayanti Bridal
            ['vendor_id' => 36, 'category_id' => 2], //MUA
            ['vendor_id' => 36, 'category_id' => 3], //Gown
            ['vendor_id' => 36, 'category_id' => 9], //Hair Styling
            ['vendor_id' => 36, 'category_id' => 10], //Headpiece
            ['vendor_id' => 36, 'category_id' => 11], //Robe & Veil

            //House of Lea
            ['vendor_id' => 37, 'category_id' => 2], //MUA
            ['vendor_id' => 37, 'category_id' => 3], //Gown
            ['vendor_id' => 37, 'category_id' => 9], //Hair Styling
            ['vendor_id' => 37, 'category_id' => 10], //Headpiece
            ['vendor_id' => 37, 'category_id' => 11], //Robe & Veil
            ['vendor_id' => 37, 'category_id' => 18], //Wedding Car

            //Ovan Putri
            ['vendor_id' => 38, 'category_id' => 2], //MUA
            ['vendor_id' => 38, 'category_id' => 3], //Gown
            ['vendor_id' => 38, 'category_id' => 9], //Hair Styling
            ['vendor_id' => 38, 'category_id' => 10], //Headpiece
            ['vendor_id' => 38, 'category_id' => 11], //Robe & Veil

            //Reine Atelier
            ['vendor_id' => 39, 'category_id' => 2], //MUA
            ['vendor_id' => 39, 'category_id' => 3], //Gown
            ['vendor_id' => 39, 'category_id' => 9], //Hair Styling
            ['vendor_id' => 39, 'category_id' => 10], //Headpiece
            ['vendor_id' => 39, 'category_id' => 11], //Robe & Veil

            //Sanjin Suits
            ['vendor_id' => 40, 'category_id' => 4], //Suit
            ['vendor_id' => 40, 'category_id' => 12], //Tie

            //Bie Hin Tailor
            ['vendor_id' => 41, 'category_id' => 4], //Suit
            ['vendor_id' => 41, 'category_id' => 12], //Tie

            //Michael Christian
            ['vendor_id' => 42, 'category_id' => 28], //MC

            //Juan Filbert
            ['vendor_id' => 43, 'category_id' => 28], //MC

            //Fransiskus Nduti
            ['vendor_id' => 44, 'category_id' => 28], //MC

            //Charly Hong
            ['vendor_id' => 45, 'category_id' => 28], //MC

            //Jeff Liu
            ['vendor_id' => 46, 'category_id' => 28], //MC

            //David Funata
            ['vendor_id' => 47, 'category_id' => 28], //MC

            //Piniela Sutandi
            ['vendor_id' => 48, 'category_id' => 28], //MC

            //Freshtunes
            ['vendor_id' => 49, 'category_id' => 33], //Band

            //Glimpse Music
            ['vendor_id' => 50, 'category_id' => 33], //Band

            //Sweet Acoustic Band
            ['vendor_id' => 51, 'category_id' => 33], //Band

            //Groovy Cake
            ['vendor_id' => 52, 'category_id' => 32], //Wedding Cake

            //Its cake
            ['vendor_id' => 53, 'category_id' => 32], //Wedding Cake

            //Clarity Production
            ['vendor_id' => 54, 'category_id' => 42], //Photobooth

            //Tini Booth
            ['vendor_id' => 55, 'category_id' => 42], //Photobooth

            //Athalia Usherettes
            ['vendor_id' => 56, 'category_id' => 37], //Usherettes

            //Pribadi
            ['vendor_id' => 57, 'category_id' => 2], //MUA
            ['vendor_id' => 57, 'category_id' => 3], //Gown
            ['vendor_id' => 57, 'category_id' => 4], //Suit
            ['vendor_id' => 57, 'category_id' => 9], //Hair Styling
            ['vendor_id' => 57, 'category_id' => 10], //Headpiece
            ['vendor_id' => 57, 'category_id' => 12], //Tie
            ['vendor_id' => 57, 'category_id' => 16], //Ring Box

            //Novotel Samator East Surabaya Hotel
            ['vendor_id' => 58, 'category_id' => 1], //Hotel
            ['vendor_id' => 58, 'category_id' => 22], //Venue
            ['vendor_id' => 58, 'category_id' => 26], //LED
            ['vendor_id' => 58, 'category_id' => 27], //LCD
            ['vendor_id' => 58, 'category_id' => 20], //Meal Crew
            ['vendor_id' => 58, 'category_id' => 13], //Room Decoraition

            //Depplight
            ['vendor_id' => 59, 'category_id' => 7], //Keepsake

            //Bethany Church Nginden
            ['vendor_id' => 60, 'category_id' => 19], //Church
            ['vendor_id' => 60, 'category_id' => 31], //Church Decoration

            //Evergreen Cake
            ['vendor_id' => 61, 'category_id' => 32], //Wedding Cake

            //Glow Effect
            ['vendor_id' => 62, 'category_id' => 38], //Effect
            ['vendor_id' => 62, 'category_id' => 24], //Lighting

            //Manifest
            ['vendor_id' => 63, 'category_id' => 33], //Band

            //Ann Pagar Ayu
            ['vendor_id' => 64, 'category_id' => 37], //Usherettes

            //Trikarta
            ['vendor_id' => 65, 'category_id' => 41], //Souvenir

            //Sempurna Invitations
            ['vendor_id' => 66, 'category_id' => 39], //Invitation

            //Calief Invitation
            ['vendor_id' => 67, 'category_id' => 39], //Invitation

            //Si Manten
            ['vendor_id' => 68, 'category_id' => 40], //Digital Guest Book

            //Stefany Hyperstage Visual
            ['vendor_id' => 69, 'category_id' => 29], //Animation

            //Nasi Campur Tambak Bayan
            ['vendor_id' => 70, 'category_id' => 21], //Guest Lunch

            //Four Clover
            ['vendor_id' => 71, 'category_id' => 14], //Hand Bouquet
            ['vendor_id' => 71, 'category_id' => 15], //Corsage

            //Retro Magis
            ['vendor_id' => 72, 'category_id' => 6], //Photographer
            ['vendor_id' => 72, 'category_id' => 5], //Videographer

            //Han Palace
            ['vendor_id' => 73, 'category_id' => 22], //Venue

            //Xiang Fu Hai
            ['vendor_id' => 74, 'category_id' => 22], //Venue

            //Double Tree
            ['vendor_id' => 75, 'category_id' => 22], //Venue

            //Savore
            ['vendor_id' => 76, 'category_id' => 22], //Venue

            //Vasa Hotel
            ['vendor_id' => 77, 'category_id' => 1], //Venue
        ];

        foreach (array_chunk($categoryVendor, 50) as $chunk) {
            DB::table('category_vendor')->insert($chunk);
        }

        $vendorPortfolios = [
            //Possa Wedding
            ['vendor_id' => 18, 'title' => 'Wedding of Wandy & Vira', 'image_path' => 'vendor_portfolios/porto-possa1.png', 'description' => 'Beautiful wedding documentation captured by Possa Wedding.', 'created_at' => now(), 'updated_at' => now()],
            ['vendor_id' => 18, 'title' => 'Wedding of Wandy & Vira', 'image_path' => 'vendor_portfolios/porto-possa2.png', 'description' => 'Capturing the genuine emotions of your special day.', 'created_at' => now(), 'updated_at' => now()],
            ['vendor_id' => 18, 'title' => 'Wedding of Yosua & Jessica', 'image_path' => 'vendor_portfolios/porto-possa4.png', 'description' => 'Every detail of your wedding, perfectly framed.', 'created_at' => now(), 'updated_at' => now()],
            ['vendor_id' => 18, 'title' => 'Wedding of Yosua & Jessica', 'image_path' => 'vendor_portfolios/porto-possa5.png', 'description' => 'Candid shots that tell a thousand words.', 'created_at' => now(), 'updated_at' => now()],
            ['vendor_id' => 18, 'title' => 'Wedding of Yosua & Jessica', 'image_path' => 'vendor_portfolios/porto-possa6.png', 'description' => 'Professional lighting and composition for every moment.', 'created_at' => now(), 'updated_at' => now()],
            ['vendor_id' => 18, 'title' => 'Wedding of Yosua & Jessica', 'image_path' => 'vendor_portfolios/porto-possa7.png', 'description' => 'Your memories, preserved beautifully forever.', 'created_at' => now(), 'updated_at' => now()],
        ];

        foreach (array_chunk($vendorPortfolios, 50) as $chunk) {
            DB::table('vendor_portfolios')->insert($chunk);
        }
    }
}
