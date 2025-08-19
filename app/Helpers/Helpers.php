<?php

namespace App\Helpers;

use App\Models\Client;
use App\Models\Instrument;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class Helpers
{
    /**
     * Verifica si el usuario tiene un rol específico.
     *
     * @param string $role
     * @return bool
     */
    public static function checkRoles(array $roles): bool
    {
        $user = User::find(Auth::id()); // Utiliza Auth::id() directamente
    
        if (!$user) {
            return false;
        }
    
        // Iterar sobre los roles y devolver true si el usuario tiene al menos uno
        foreach ($roles as $role) {
            if ($user->hasRole($role)) {
                return true;
            }
        }
    
        // Si no tiene ninguno de los roles, devuelve false
        return false;
    }

    public static function getCountClients()
    {

        $count = Client::count();
        return $count;
    }

    public static function getCountPayments()
    {

        $count = Payment::count();
        return $count;
    }
    public static function getCountUsers()
    {

        $count = User::count();
        return $count;
    }

    public static function getCountCanceled()
    {

        $count = Instrument::where('status','canceled')->count();
        return $count;
    }
    
    
    public static function getCountActive()
    {

        $count = Instrument::where('status','active')->count();
        return $count;
    }
    
    
    
    

    



    public static function getMenuEnable($route,$param=null)
    {
     
        
       try {
        if($param!=null){
            if (request()->is($route . '/'. $param) ) {
                return true;
            } else {
                return false;
            }
        }else{
            if (request()->is($route) || request()->is($route . '/*') || request()->route()->getName()==$route ) {
                return true;
            } else {
                return false;
            }
        }

       } catch (\Throwable $th) {
       return false;
       }
    
    }

    public static function getBreadCrumbs($breadcrumbsList)
    {


        $html = "";
        $htmlInner = "";
        for ($i = 0; $i < count($breadcrumbsList); $i++) {
            if ($i == 0)
                $html .= " <h3 class='kt-subheader__title'>" . $breadcrumbsList[$i]['name'] . "</h3>";
            if ($i == 1)
                $html .= " <span class='kt-subheader__separator kt-hidden'></span>";
            if ($i >= 1)
                $htmlInner .= ' <span class="kt-subheader__breadcrumbs-separator"></span><a href="' . $breadcrumbsList[$i]['route'] . '" class="kt-subheader__breadcrumbs-link"> ' . $breadcrumbsList[$i]['name'] . ' </a>';
        }

        if ($htmlInner != "") {
            $html .= '<div class="kt-subheader__breadcrumbs">
<a href="#" class="kt-subheader__breadcrumbs-home"><i class="flaticon2-shelter"></i></a>';
            $html .= $htmlInner;
            $html .= "</div>";
        }
        return $html;
    }


    public static function getCountries(){
      
$countries = [
    "Afganistán", "Albania", "Alemania", "Andorra", "Angola", "Antigua y Barbuda", "Arabia Saudita",
    "Argelia", "Argentina", "Armenia", "Australia", "Austria", "Azerbaiyán", "Bahamas", "Bangladés",
    "Barbados", "Baréin", "Bélgica", "Belice", "Benín", "Bielorrusia", "Birmania", "Bolivia", "Bosnia y Herzegovina",
    "Botsuana", "Brasil", "Brunéi", "Bulgaria", "Burkina Faso", "Burundi", "Bután", "Cabo Verde",
    "Camboya", "Camerún", "Canadá", "Catar", "Chad", "Chile", "China", "Chipre", "Colombia", "Comoras",
    "Corea del Norte", "Corea del Sur", "Costa de Marfil", "Costa Rica", "Croacia", "Cuba", "Dinamarca",
    "Dominica", "Ecuador", "Egipto", "El Salvador", "Emiratos Árabes Unidos", "Eritrea", "Eslovaquia", 
    "Eslovenia", "España", "Estados Unidos", "Estonia", "Esuatini", "Etiopía", "Filipinas", "Finlandia",
    "Fiyi", "Francia", "Gabón", "Gambia", "Georgia", "Ghana", "Granada", "Grecia", "Guatemala", "Guyana",
    "Guinea", "Guinea ecuatorial", "Guinea-Bisáu", "Haití", "Honduras", "Hungría", "India", "Indonesia",
    "Irak", "Irán", "Irlanda", "Islandia", "Islas Marshall", "Islas Salomón", "Israel", "Italia", "Jamaica",
    "Japón", "Jordania", "Kazajistán", "Kenia", "Kirguistán", "Kiribati", "Kosovo", "Kuwait", "Laos",
    "Lesoto", "Letonia", "Líbano", "Liberia", "Libia", "Liechtenstein", "Lituania", "Luxemburgo", "Madagascar",
    "Malasia", "Malaui", "Maldivas", "Malí", "Malta", "Marruecos", "Mauricio", "Mauritania", "México",
    "Micronesia", "Moldavia", "Mónaco", "Mongolia", "Montenegro", "Mozambique", "Namibia", "Nauru", "Nepal",
    "Nicaragua", "Níger", "Nigeria", "Noruega", "Nueva Zelanda", "Omán", "Países Bajos", "Pakistán", 
    "Palaos", "Panamá", "Papúa Nueva Guinea", "Paraguay", "Perú", "Polonia", "Portugal", "Reino Unido",
    "República Centroafricana", "República Checa", "República Dominicana", "Ruanda", "Rumania", "Rusia",
    "Samoa", "San Cristóbal y Nieves", "San Marino", "San Vicente y las Granadinas", "Santa Lucía",
    "Santo Tomé y Príncipe", "Senegal", "Serbia", "Seychelles", "Sierra Leona", "Singapur", "Siria", 
    "Somalia", "Sri Lanka", "Sudáfrica", "Sudán", "Sudán del Sur", "Suecia", "Suiza", "Surinam",
    "Tailandia", "Tanzania", "Tayikistán", "Timor Oriental", "Togo", "Tonga", "Trinidad y Tobago", 
    "Túnez", "Turkmenistán", "Turquía", "Tuvalu", "Ucrania", "Uganda", "Uruguay", "Uzbekistán",
    "Vanuatu", "Vaticano", "Venezuela", "Vietnam", "Yemen", "Yibuti", "Zambia", "Zimbabue"
];


return $countries;

    }

}
