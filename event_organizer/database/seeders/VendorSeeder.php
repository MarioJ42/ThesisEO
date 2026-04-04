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
                'name' => 'XO Palace Ballroom',
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
                'name' => 'Novotel Samator Ballroom',
                'address' => 'Jl. Raya Kedung Baruk No.26-28, Kedung Baruk, Kec. Rungkut, Surabaya, Jawa Timur 60298',
                'instagram' => '@novotel_samator',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 18,
                'name' => 'Possa',
                'address' => 'Surabaya',
                'instagram' => '@possawedding',
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
                'name' => 'Biehin Tailor',
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
        ];

        DB::table('vendors')->insert($vendors);

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

            //Laviere Lounge (Morazen)
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

            //XO Palace Ballroom
            ['vendor_id' => 14, 'category_id' => 22], //Venue

            //Santika Premiere Ballroom
            ['vendor_id' => 15, 'category_id' => 22], //Venue

            //Morazen Ballroom
            ['vendor_id' => 16, 'category_id' => 22], //Venue

            //Novotel Samator Ballroom
            ['vendor_id' => 17, 'category_id' => 22], //Venue

            //Possa
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

            //Dave Gallery
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

            //Best Deccoration
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

            //Biehin Tailor
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

            //Clarity
            ['vendor_id' => 54, 'category_id' => 42], //Photobooth

            //Tini Booth
            ['vendor_id' => 55, 'category_id' => 42], //Photobooth

            //Athalia Usherettes
            ['vendor_id' => 56, 'category_id' => 37], //Usherettes

            //Probadi
            ['vendor_id' => 57, 'category_id' => 2], //MUA
            ['vendor_id' => 57, 'category_id' => 3], //Gown
            ['vendor_id' => 57, 'category_id' => 4], //Suit
            ['vendor_id' => 57, 'category_id' => 9], //Hair Styling
            ['vendor_id' => 57, 'category_id' => 10], //Headpiece
            ['vendor_id' => 57, 'category_id' => 12], //Tie
            ['vendor_id' => 57, 'category_id' => 16], //Ring Box
        ];

        DB::table('category_vendor')->insert($categoryVendor);
    }
}
