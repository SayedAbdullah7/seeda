<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VehicleColors extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $vehiclesColor = [

            [
                "id"=>"1",
                "name"=>"Metallic Graphite Black",
                "code"=>"0xff1c1d21",
            ],
            [
                "id"=>"2",
                "name"=>"Metallic Black Steal",
                "code"=>"0xff32383d",

            ],
            [
                "id"=>"3",
                "name"=>"Metallic Dark Silver",
                "code"=>"0xff454b4f",

            ],
            [
                "id"=>"4",
                "name"=>"Metallic Silver",
                "code"=>"0xff999da0",

            ],
            [
                "id"=>"5",
                "name"=>"Metallic Blue Silver",
                "code"=>"0xffc2c4c6",

            ],
            [
                "id"=>"6",
                "name"=>"Metallic Steel Gray",
                "code"=>"0xff979a97",

            ],
            [
                "id"=>"7",
                "name"=>"Metallic Shadow Silver",
                "code"=>"0xff637380",

            ],
            [
                "id"=>"8",
                "name"=>"Metallic Stone Silver",
                "code"=>"0xff63625c",

            ],
            [
                "id"=>"9",
                "name"=>"Metallic Midnight Silver",
                "code"=>"0xff3c3f47",

            ],
            [
                "id"=>"10",
                "name"=>"Metallic Gun Metal",
                "code"=>"0xff444e54",

            ],
            [
                "id"=>"11",
                "name"=>"Metallic Anthracite Grey",
                "code"=>"0xff1d2129",

            ],
            [
                "id"=>"12",
                "name"=>"Matte Black",
                "code"=>"0xff13181f",

            ],
            [
                "id"=>"13",
                "name"=>"Matte Gray",
                "code"=>"0xff26282a",

            ],
            [
                "id"=>"14",
                "name"=>"Matte Light Grey",
                "code"=>"0xff515554",

            ],
            [
                "id"=>"15",
                "name"=>"Util Black",
                "code"=>"0xff151921",

            ],
            [
                "id"=>"16",
                "name"=>"Util Black Poly",
                "code"=>"0xff1e2429",

            ],
            [
                "id"=>"17",
                "name"=>"Util Dark silver",
                "code"=>"0xff333a3c",

            ],
            [
                "id"=>"18",
                "name"=>"Util Silver",
                "code"=>"0xff8c9095",

            ],
            [
                "id"=>"19",
                "name"=>"Util Gun Metal",
                "code"=>"0xff39434d",

            ],
            [
                "id"=>"20",
                "name"=>"Util Shadow Silver",
                "code"=>"0xff506272",

            ],
            [
                "id"=>"21",
                "name"=>"Worn Black",
                "code"=>"0xff1e232f",

            ],
            [
                "id"=>"22",
                "name"=>"Worn Graphite",
                "code"=>"0xff363a3f",
            ],
            [
                "id"=>"23",
                "name"=>"Worn Silver Grey",
                "code"=>"0xffa0a199",

            ],
            [
                "id"=>"24",
                "name"=>"Worn Silver",
                "code"=>"0xffd3d3d3",
            ],
            [
                "id"=>"25",
                "name"=>"Worn Blue Silver",
                "code"=>"0xffb7bfca",

            ],
            [
                "id"=>"26",
                "name"=>"Worn Shadow Silver",
                "code"=>"0xff778794",

            ],
            [
                "id"=>"27",
                "name"=>"Metallic Red",
                "code"=>"0xffc00e1a",

            ],
            [
                "id"=>"28",
                "name"=>"Metallic Torino Red",
                "code"=>"0xffda1918",

            ],
            [
                "id"=>"29",
                "name"=>"Metallic Formula Red",
                "code"=>"0xffb6111b",

            ],
            [
                "id"=>"30",
                "name"=>"Metallic Blaze Red",
                "code"=>"0xffa51e23",

            ],
            [
                "id"=>"31",
                "name"=>"Metallic Graceful Red",
                "code"=>"0xff7b1a22",

            ],
            [
                "id"=>"32",
                "name"=>"Metallic Garnet Red",
                "code"=>"0xff8e1b1f",

            ],
            [
                "id"=>"33",
                "name"=>"Metallic Desert Red",
                "code"=>"0xff6f1818",

            ],
            [
                "id"=>"34",
                "name"=>"Metallic Cabernet Red",
                "code"=>"0xff49111d",

            ],
            [
                "id"=>"35",
                "name"=>"Metallic Candy Red",
                "code"=>"0xffb60f25",

            ],
            [
                "id"=>"36",
                "name"=>"Metallic Sunrise Orange",
                "code"=>"0xffd44a17",

            ],
            [
                "id"=>"37",
                "name"=>"Metallic Classic Gold",
                "code"=>"0xffc2944f",

            ],
            [
                "id"=>"38",
                "name"=>"Metallic Orange",
                "code"=>"0xfff78616",

            ],
            [
                "id"=>"39",
                "name"=>"Matte Red",
                "code"=>"0xffcf1f21",

            ],
            [
                "id"=>"40",
                "name"=>"Matte Dark Red",
                "code"=>"0xff732021",

            ],
            [
                "id"=>"41",
                "name"=>"Matte Orange",
                "code"=>"0xfff27d20",

            ],
            [
                "id"=>"42",
                "name"=>"Matte Yellow",
                "code"=>"0xffffc91f",

            ],
            [
                "id"=>"43",
                "name"=>"Util Red",
                "code"=>"0xff9c1016",

            ],
            [
                "id"=>"44",
                "name"=>"Util Bright Red",
                "code"=>"0xffde0f18",

            ],
            [
                "id"=>"45",
                "name"=>"Util Garnet Red",
                "code"=>"0xff8f1e17",

            ],
            [
                "id"=>"46",
                "name"=>"Worn Red",
                "code"=>"0xffa94744",

            ],
            [
                "id"=>"47",
                "name"=>"Worn Golden Red",
                "code"=>"0xffb16c51",

            ],
            [
                "id"=>"48",
                "name"=>"Worn Dark Red",
                "code"=>"0xff371c25",

            ],
            [
                "id"=>"49",
                "name"=>"Metallic Dark Green",
                "code"=>"0xff132428",

            ],
            [
                "id"=>"50",
                "name"=>"Metallic Racing Green",
                "code"=>"0xff122e2b",

            ],
            [
                "id"=>"51",
                "name"=>"Metallic Sea Green",
                "code"=>"0xff12383c",

            ],
            [
                "id"=>"52",
                "name"=>"Metallic Olive Green",
                "code"=>"0xff31423f",

            ],
            [
                "id"=>"53",
                "name"=>"Metallic Green",
                "code"=>"0xff155c2d",

            ],
            [
                "id"=>"54",
                "name"=>"Metallic Gasoline Blue Green",
                "code"=>"0xff1b6770",

            ],
            [
                "id"=>"55",
                "name"=>"Matte Lime Green",
                "code"=>"0xff66b81f",

            ],
            [
                "id"=>"56",
                "name"=>"Util Dark Green",
                "code"=>"0xff22383e",

            ],
            [
                "id"=>"57",
                "name"=>"Util Green",
                "code"=>"0xff1d5a3f",

            ],
            [
                "id"=>"58",
                "name"=>"Worn Dark Green",
                "code"=>"0xff2d423f",

            ],
            [
                "id"=>"59",
                "name"=>"Worn Green",
                "code"=>"0xff45594b",

            ],
            [
                "id"=>"60",
                "name"=>"Worn Sea Wash",
                "code"=>"0xff65867f",

            ],
            [
                "id"=>"61",
                "name"=>"Metallic Midnight Blue",
                "code"=>"0xff222e46",

            ],
            [
                "id"=>"62",
                "name"=>"Metallic Dark Blue",
                "code"=>"0xff233155",

            ],
            [
                "id"=>"63",
                "name"=>"Metallic Saxony Blue",
                "code"=>"0xff304c7e",

            ],
            [
                "id"=>"64",
                "name"=>"Metallic Blue",
                "code"=>"0xff47578f",

            ],
            [
                "id"=>"65",
                "name"=>"Metallic Mariner Blue",
                "code"=>"0xff637ba7",

            ],
            [
                "id"=>"66",
                "name"=>"Metallic Harbor Blue",
                "code"=>"0xff394762",

            ],
            [
                "id"=>"67",
                "name"=>"Metallic Diamond Blue",
                "code"=>"0xffd6e7f1",

            ],
            [
                "id"=>"68",
                "name"=>"Metallic Surf Blue",
                "code"=>"0xff76afbe",

            ],
            [
                "id"=>"69",
                "name"=>"Metallic Nautical Blue",
                "code"=>"0xff345e72",

            ],
            [
                "id"=>"70",
                "name"=>"Metallic Bright Blue",
                "code"=>"0xff0b9cf1",

            ],
            [
                "id"=>"71",
                "name"=>"Metallic Purple Blue",
                "code"=>"0xff2f2d52",

            ],
            [
                "id"=>"72",
                "name"=>"Metallic Spinnaker Blue",
                "code"=>"0xff282c4d",

            ],
            [
                "id"=>"73",
                "name"=>"Metallic Ultra Blue",
                "code"=>"0xff2354a1",

            ],
            [
                "id"=>"74",
                "name"=>"Metallic Bright Blue",
                "code"=>"0xff6ea3c6",
            ],
            [
                "id"=>"75",
                "name"=>"Util Dark Blue",
                "code"=>"0xff112552",

            ],
            [
                "id"=>"76",
                "name"=>"Util Midnight Blue",
                "code"=>"0xff1b203e",

            ],
            [
                "id"=>"77",
                "name"=>"Util Blue",
                "code"=>"0xff275190",

            ],
            [
                "id"=>"78",
                "name"=>"Util Sea Foam Blue",
                "code"=>"0xff608592",

            ],
            [
                "id"=>"79",
                "name"=>"Util Lightning blue",
                "code"=>"0xff2446a8",
            ],
            [
                "id"=>"80",
                "name"=>"Util Maui Blue Poly",
                "code"=>"0xff4271e1",

            ],
            [
                "id"=>"81",
                "name"=>"Util Bright Blue",
                "code"=>"0xff3b39e0",

            ],
            [
                "id"=>"82",
                "name"=>"Matte Dark Blue",
                "code"=>"0xff1f2852",

            ],
            [
                "id"=>"83",
                "name"=>"Matte Blue",
                "code"=>"0xff253aa7",

            ],
            [
                "id"=>"84",
                "name"=>"Matte Midnight Blue",
                "code"=>"0xff1c3551",

            ],
            [
                "id"=>"85",
                "name"=>"Worn Dark blue",
                "code"=>"0xff4c5f81",

            ],
            [
                "id"=>"86",
                "name"=>"Worn Blue",
                "code"=>"0xff58688e",

            ],
            [
                "id"=>"87",
                "name"=>"Worn Light blue",
                "code"=>"0xff74b5d8",

            ],
            [
                "id"=>"88",
                "name"=>"Metallic Taxi Yellow",
                "code"=>"0xffffcf20",

            ],
            [
                "id"=>"89",
                "name"=>"Metallic Race Yellow",
                "code"=>"0xfffbe212",

            ],
            [
                "id"=>"90",
                "name"=>"Metallic Bronze",
                "code"=>"0xff916532",

            ],
            [
                "id"=>"91",
                "name"=>"Metallic Yellow Bird",
                "code"=>"0xffe0e13d",

            ],
            [
                "id"=>"92",
                "name"=>"Metallic Lime",
                "code"=>"0xff98d223",

            ],
            [
                "id"=>"93",
                "name"=>"Metallic Champagne",
                "code"=>"0xff9b8c78",

            ],
            [
                "id"=>"94",
                "name"=>"Metallic Pueblo Beige",
                "code"=>"0xff503218",

            ],
            [
                "id"=>"95",
                "name"=>"Metallic Dark Ivory",
                "code"=>"0xff473f2b",

            ],
            [
                "id"=>"96",
                "name"=>"Metallic Choco Brown",
                "code"=>"0xff221b19",

            ],
            [
                "id"=>"97",
                "name"=>"Metallic Golden Brown",
                "code"=>"0xff653f23",

            ],
            [
                "id"=>"98",
                "name"=>"Metallic Light Brown",
                "code"=>"0xff775c3e",

            ],
            [
                "id"=>"99",
                "name"=>"Metallic Straw Beige",
                "code"=>"0xffac9975",

            ],
            [
                "id"=>"100",
                "name"=>"Metallic Moss Brown",
                "code"=>"0xff6c6b4b",

            ],
            [
                "id"=>"101",
                "name"=>"Metallic Biston Brown",
                "code"=>"0xff402e2b",

            ],
            [
                "id"=>"102",
                "name"=>"Metallic Beechwood",
                "code"=>"0xffa4965f",

            ],
            [
                "id"=>"103",
                "name"=>"Metallic Dark Beechwood",
                "code"=>"0xff46231a",

            ],
            [
                "id"=>"104",
                "name"=>"Metallic Choco Orange",
                "code"=>"0xff752b19",

            ],
            [
                "id"=>"105",
                "name"=>"Metallic Beach Sand",
                "code"=>"0xffbfae7b",

            ],
            [
                "id"=>"106",
                "name"=>"Metallic Sun Bleeched Sand",
                "code"=>"0xffdfd5b2",

            ],
            [
                "id"=>"107",
                "name"=>"Metallic Cream",
                "code"=>"0xfff7edd5",

            ],
            [
                "id"=>"108",
                "name"=>"Util Brown",
                "code"=>"0xff3a2a1b",

            ],
            [
                "id"=>"109",
                "name"=>"Util Medium Brown",
                "code"=>"0xff785f33",

            ],
            [
                "id"=>"110",
                "name"=>"Util Light Brown",
                "code"=>"0xffb5a079",

            ],
            [
                "id"=>"111",
                "name"=>"Metallic White",
                "code"=>"0xfffffff6",

            ],
            [
                "id"=>"112",
                "name"=>"Metallic Frost White",
                "code"=>"0xffeaeaea",

            ],
            [
                "id"=>"113",
                "name"=>"Worn Honey Beige",
                "code"=>"0xffb0ab94",

            ],
            [
                "id"=>"114",
                "name"=>"Worn Brown",
                "code"=>"0xff453831",

            ],
            [
                "id"=>"115",
                "name"=>"Worn Dark Brown",
                "code"=>"0xff2a282b",

            ],
            [
                "id"=>"116",
                "name"=>"Worn straw beige",
                "code"=>"0xff726c57",

            ],
            [
                "id"=>"117",
                "name"=>"Brushed Steel",
                "code"=>"0xff6a747c",
            ],
            [
                "id"=>"118",
                "name"=>"Brushed Black steel",
                "code"=>"0xff354158",

            ],
            [
                "id"=>"119",
                "name"=>"Brushed Aluminium",
                "code"=>"0xff9ba0a8",

            ],
            [
                "id"=>"120",
                "name"=>"Chrome",
                "code"=>"0xff5870a1",

            ],
            [
                "id"=>"121",
                "name"=>"Worn Off White",
                "code"=>"0xffeae6de",

            ],
            [
                "id"=>"122",
                "name"=>"Util Off White",
                "code"=>"0xffdfddd0",

            ],
            [
                "id"=>"123",
                "name"=>"Worn Orange",
                "code"=>"0xfff2ad2e",

            ],
            [
                "id"=>"124",
                "name"=>"Worn Light Orange",
                "code"=>"0xfff9a458",

            ],
            [
                "id"=>"125",
                "name"=>"Metallic Securicor Green",
                "code"=>"0xff83c566",

            ],
            [
                "id"=>"126",
                "name"=>"Worn Taxi Yellow",
                "code"=>"0xfff1cc40",

            ],
            [
                "id"=>"127",
                "name"=>"police car blue",
                "code"=>"0xff4cc3da",

            ],
            [
                "id"=>"128",
                "name"=>"Matte Green",
                "code"=>"0xff4e6443",

            ],
            [
                "id"=>"129",
                "name"=>"Matte Brown",
                "code"=>"0xffbcac8f",

            ],
            [
                "id"=>"130",
                "name"=>"Worn Orange",
                "code"=>"0xfff8b658",

            ],
            [
                "id"=>"131",
                "name"=>"Matte White",
                "code"=>"0xfffcf9f1",

            ],
            [
                "id"=>"132",
                "name"=>"Worn White",
                "code"=>"0xfffffffb",

            ],
            [
                "id"=>"133",
                "name"=>"Worn Olive Army Green",
                "code"=>"0xff81844c",

            ],
            [
                "id"=>"134",
                "name"=>"Pure White",
                "code"=>"0xffffffff",

            ],
            [
                "id"=>"135",
                "name"=>"Hot Pink",
                "code"=>"0xfff21f99",
            ],
            [
                "id"=>"136",
                "name"=>"Salmon pink",
                "code"=>"0xfffdd6cd",

            ],
            [
                "id"=>"137",
                "name"=>"Metallic Vermillion Pink",
                "code"=>"0xffdf5891",
            ],
            [
                "id"=>"138",
                "name"=>"Orange",
                "code"=>"0xfff6ae20",

            ],
            [
                "id"=>"139",
                "name"=>"Green",
                "code"=>"0xffb0ee6e",

            ],
            [
                "id"=>"140",
                "name"=>"Blue",
                "code"=>"0xff08e9fa",

            ],
            [
                "id"=>"141",
                "name"=>"Mettalic Black Blue",
                "code"=>"0xff0a0c17",

            ],
            [
                "id"=>"142",
                "name"=>"Metallic Black Purple",
                "code"=>"0xff0c0d18",

            ],
            [
                "id"=>"143",
                "name"=>"Metallic Black Red",
                "code"=>"0xff0e0d14",

            ],
            [
                "id"=>"144",
                "name"=>"hunter green",
                "code"=>"0xff9f9e8a",

            ],
            [
                "id"=>"145",
                "name"=>"Metallic Purple",
                "code"=>"0xff621276",

            ],
            [
                "id"=>"146",
                "name"=>"Metaillic V Dark Blue",
                "code"=>"0xff0b1421",

            ],
            [
                "id"=>"147",
                "name"=>"MODSHOP BLACK1",
                "code"=>"0xff11141a",

            ],
            [
                "id"=>"148",
                "name"=>"Matte Purple",
                "code"=>"0xff6b1f7b",

            ],
            [
                "id"=>"149",
                "name"=>"Matte Dark Purple",
                "code"=>"0xff1e1d22",

            ],
            [
                "id"=>"150",
                "name"=>"Metallic Lava Red",
                "code"=>"0xffbc1917",

            ],
            [
                "id"=>"151",
                "name"=>"Matte Forest Green",
                "code"=>"0xff2d362a",
            ],
            [
                "id"=>"152",
                "name"=>"Matte Olive Drab",
                "code"=>"0xff696748",

            ],
            [
                "id"=>"153",
                "name"=>"Matte Desert Brown",
                "code"=>"0xff7a6c55",

            ],
            [
                "id"=>"154",
                "name"=>"Matte Desert Tan",
                "code"=>"0xffc3b492",

            ],
            [
                "id"=>"155",
                "name"=>"Matte Foilage Green",
                "code"=>"0xff5a6352",

            ],
            [
                "id"=>"156",
                "name"=>"DEFAULT ALLOY COLOR",
                "code"=>"0xff81827f",

            ],
            [
                "id"=>"157",
                "name"=>"Epsilon Blue",
                "code"=>"0xffafd6e4",

            ],
            [
                "id"=>"158",
                "name"=>"Pure Gold",
                "code"=>"0xff7a6440",

            ],
            [
                "id"=>"159",
                "name"=>"Brushed Gold",
                "code"=>"0xff7f6a48",

            ],
            [
                "id"=>"160",
                "name"=>"Metallic Black",
                "code"=>"0xff0d1116",

            ]
        ];

        DB::table("vehicle_colors")->insert($vehiclesColor);
    }
}
