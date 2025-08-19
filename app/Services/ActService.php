<?php

namespace App\Services;

use App\Models\Act;
use App\Models\Appearer;
use App\Models\Client;
use App\Models\Denomination;
use App\Models\Instrument;
use Carbon\Carbon;
use NumberFormatter;

class ActService
{

    /**
     * Extrae el día, mes y año de una fecha MySQL (YYYY-MM-DD)
     *
     * @param string $fecha_creacion
     * @return array
     */


    private static function obtenerFecha($fecha_creacion)
    {
        $fecha = Carbon::parse($fecha_creacion);
        $formatter = new NumberFormatter('es', NumberFormatter::SPELLOUT);

        $dia = $fecha->day == 1 ? 'PRIMER' : ucfirst($formatter->format($fecha->day));
        $anio = ucfirst($formatter->format($fecha->year));

        return self::construirFechaFormal($dia,$fecha->translatedFormat('F'),$anio);
    }

    private static function construirFechaFormal($dia,$mes,$anio)
    {
        // Si el día es "PRIMERO", cambia la estructura
        if ($dia === 'PRIMER') {
            return "AL " . self::validarDato($dia) . " DÍA DEL MES DE " . self::validarDato($mes) . " DEL AÑO " . self::validarDato($anio);
        }

        // Para cualquier otro día, usa la estructura original
        return "A LOS " . self::validarDato($dia) . " DÍAS DEL MES DE " . self::validarDato($mes) . " DEL AÑO " . self::validarDato($anio);
    }

    /**
     * Verifica si una variable está vacía y la reemplaza con "_____"
     *
     * @param mixed $valor
     * @return string
     */
    private static function validarDato($valor)
    {
        return empty($valor) ? "_____" : $valor;
    }


    public static function getFormatExtract($instrument_act)
    {

        $act = Act::where('id', $instrument_act->act_id)->first();
        $intrument = Instrument::where('id', $instrument_act->instrument_id)->first();
        $client = Client::where('id', $instrument_act->client_id)->first();

        $numero_poliza = self::numeroALetras( $intrument->no);
        $fecha_creacion = $intrument->created_at;
        $nombre_cliente = $client->name;
        $tipo_sociedad = self::getDenomination($client);
        $fecha_asamblea = self::obtenerFecha($instrument_act->created_at);
        $nombres_solicitantes = self::getSolicitantes($instrument_act);
        $cargo_solicitante = "DELEGADO ESPECIAL";

        $type_instrument = $intrument->type;

        $format = "";
        if ($act->extract != "yes") {
            return "";
        }

        if ($act->act == "Formalización") {
            $tipo_formalizacion = $instrument_act->formalization_type;
            $format = self::generateFormalizacion($type_instrument, $numero_poliza, $fecha_creacion, $tipo_formalizacion, $nombre_cliente, $tipo_sociedad, $fecha_asamblea, $nombres_solicitantes, $cargo_solicitante);
        }

        if ($act->act == "Constitución") {
            $format = self::generateConstitucion($type_instrument, $numero_poliza, $fecha_creacion, $nombre_cliente, $tipo_sociedad, $nombres_solicitantes);
        }

        if ($act->act == "Cotejo") {

            $format = self::generateCotejo($type_instrument, $numero_poliza, $fecha_creacion, $nombre_cliente);
        }


        if ($act->act == "Notificación") {
            $nombre_notificado = $instrument_act->notified_person;
            $detalle_notificacion = $instrument_act->notification_subject;
            $format = self::generateNotificacion($type_instrument, $numero_poliza, $fecha_creacion, $nombre_notificado, $detalle_notificacion, $nombres_solicitantes);
        }


        if ($act->act == "Ratificación") {
            $documento_ratificado = $instrument_act->document_ratified;
            $format = self::generateRatificacion($type_instrument, $numero_poliza, $fecha_creacion, $documento_ratificado, $nombres_solicitantes);
        }


        if ($act->act == "Fe de hechos") {
            $detalle_hecho = $instrument_act->fact_recorded;

            $format = self::generateFeDeHechos($type_instrument, $numero_poliza, $fecha_creacion, $detalle_hecho, $instrument_act->legal_representative, $nombre_cliente . " " . $tipo_sociedad);
        }

        if ($act->act == "Designación") {

            $format = self::generateDesignacion($type_instrument, $fecha_creacion, $numero_poliza, $nombre_cliente, $tipo_sociedad, $nombres_solicitantes);
        }
        if ($act->act == "Revocación") {

            $format = self::generateRevocacion($type_instrument, $fecha_creacion, $numero_poliza, $nombre_cliente, $tipo_sociedad, $nombres_solicitantes);
        }

        if ($act->act == "Intermediario") {

            $format = self::generateDefault($type_instrument, $fecha_creacion, $numero_poliza, $nombres_solicitantes,  $act->act);
        }

        if ($act->act == "Formalización de Contrato / Convenio") {

            $format = self::generateFormalizacionContrato($type_instrument, $fecha_creacion, $numero_poliza, $instrument_act->formalization_contract, $instrument_act->of, $nombres_solicitantes);
        }
        if ($act->act == "Compulsa") {
            if ($client->person_type == "moral") {
                $legal_representative = $instrument_act->legal_representative;
                $on_representation = $client->name . " " . self::getDenomination($client);
                $solicitante = $legal_representative . " EN REPRESENTACIÓN DE " . $on_representation;
            } else {
                $solicitante = $client->name;
            }
            $format = self::generateCompulsa($type_instrument, $fecha_creacion, $numero_poliza, $solicitante);
        }


        if ($act->act == "Certificado de depósitos de acciones") {
            if ($client->person_type == "moral") {
                $legal_representative = $instrument_act->legal_representative;
                $on_representation = $client->name . " " . self::getDenomination($client);
                $solicitante = $legal_representative . " EN REPRESENTACIÓN DE " . $on_representation;
            } else {
                $solicitante = $client->name;
            }
            $format = self::generateCertificadoDepositoAcciones($type_instrument, $fecha_creacion, $numero_poliza, $solicitante);
        }
        if ($act->act == "Declaraciones mercantiles") {
            if ($client->person_type == "moral") {
                $legal_representative = $instrument_act->legal_representative;
                $on_representation = $client->name . " " . self::getDenomination($client);
                $solicitante = $legal_representative . " EN REPRESENTACIÓN DE " . $on_representation;
            } else {
                $solicitante = $client->name;
            }
            $format = self::generateDeclaracionesMercantiles($type_instrument, $fecha_creacion, $numero_poliza, $instrument_act->mercantile_declarations, $solicitante);
        }


        if ($act->act == "Comisión mercantil") {
            if ($client->person_type == "moral") {
                $legal_representative = $instrument_act->legal_representative;
                $on_representation = $client->name . " " . self::getDenomination($client);
                $solicitante = $legal_representative . " EN REPRESENTACIÓN DE " . $on_representation;
            } else {
                $solicitante = $client->name;
            }
            $format = self::generateComisionMercantil($type_instrument, $fecha_creacion, $numero_poliza, $nombres_solicitantes, $instrument_act->in_favor_of);
        }

        if ($format == "") {
            $format = self::generateDefault($type_instrument, $fecha_creacion, $numero_poliza, $nombres_solicitantes,  $act->act);
        }






        return mb_strtoupper($format, 'UTF-8');
    }

    private static function  numeroALetras($numero)
{
    $formatter = new NumberFormatter("es", NumberFormatter::SPELLOUT);
    return ucfirst($formatter->format($numero));
}



    private static function getDenomination($client)
    {
        if ($client->person_type == "moral") {
            $denomination = Denomination::where('id', $client->denomination_id)->first();
            if ($denomination) {
                return $denomination->denomination;
            } else {
                return "";
            }
        } else {
            return "";
        }
    }

    private static function getSolicitantes($instrument_act)
    {
        $appearers = Appearer::where('instrument_act_id', $instrument_act->id)->get();
        $solicitantes = []; // Usamos un array para almacenar los nombres

        foreach ($appearers as $appearer) {
            $client = Client::where('id', $appearer->appearer)->first();

            if ($client->person_type == "moral") {
                $legal_representative = $appearer->legal_representative;
                $on_representation = $client->name . " " . self::getDenomination($client);
                $solicitante = $legal_representative . " EN REPRESENTACIÓN DE " . $on_representation;
            } else {
                $solicitante = $client->name;
            }

            $solicitantes[] = $solicitante; // Agregamos cada solicitante al array
        }

        if (count($solicitantes) > 0) {
            return  implode(", ", $solicitantes); // Concatenamos los nombres separados por ", "

        } else {
            return "_____";
        }
    }



    /**
     * Genera el formato para Formalización.
     *
     * @param string $fecha_creacion Fecha en formato YYYY-MM-DD.
     * @param string $tipo_asamblea Tipo de asamblea (ejemplo: "ORDINARIA", "EXTRAORDINARIA").
     * @param string $nombre_empresa Nombre de la empresa.
     * @param string $tipo_sociedad Tipo de sociedad (ejemplo: "S.A. de C.V.").
     * @param string $fecha_asamblea Fecha de la asamblea.
     * @param string $nombre_solicitante Nombre del solicitante.
     * @param string $cargo_solicitante Cargo del solicitante.
     * @return string
     */
    public static function generateFormalizacion($type_instrument, $numero_poliza, $fecha_creacion, $tipo_formalizacion, $nombre_cliente, $tipo_sociedad, $fecha_asamblea, $nombres_solicitantes, $cargo_solicitante)
    {
        $fecha = self::obtenerFecha($fecha_creacion);

        return $type_instrument . " NÚMERO " . self::validarDato($numero_poliza) . ", " . self::validarDato($fecha) . ", 
    SE HIZO CONSTAR LA FORMALIZACIÓN DEL ACTA DE ASAMBLEA " . self::validarDato($tipo_formalizacion) . " DE \"" . self::validarDato($nombre_cliente) . "\"
    " . self::validarDato($tipo_sociedad) . ", DE FECHA " . self::validarDato($fecha_asamblea) . ", A SOLICITUD DE " . self::validarDato($nombres_solicitantes) . " COMO " . self::validarDato($cargo_solicitante) . ".";
    }



    /**
     * Genera el formato para Constitución.
     *
     * @param string $fecha_creacion Fecha en formato YYYY-MM-DD.
     * @param string $nombre_empresa Nombre de la empresa.
     * @param string $tipo_sociedad Tipo de sociedad (ejemplo: "S.A. de C.V.").
     * @param string $nombres_otorgantes Nombres de las personas que otorgan la constitución.
     * @return string
     */
    public static function generateConstitucion($type_instrument, $numero_poliza, $fecha_creacion, $nombre_empresa, $tipo_sociedad, $nombres_otorgantes)
    {
        $fecha = self::obtenerFecha($fecha_creacion);

        return $type_instrument . " NÚMERO " . self::validarDato($numero_poliza) . ", " . self::validarDato($fecha) . ", 
    SE HIZO CONSTAR LA CONSTITUCIÓN DE \"" . self::validarDato($nombre_empresa) . "\", " . self::validarDato($tipo_sociedad) . ", QUE OTORGAN 
    LOS SEÑORES " . self::validarDato($nombres_otorgantes) . ".";
    }


    /**
     * Genera el formato para Cotejo.
     *
     * @param string $fecha_creacion Fecha en formato YYYY-MM-DD.
     * @param string $nombre_solicitante Nombre del solicitante del cotejo.
     * @return string
     */
    public static function generateCotejo($type_instrument, $numero_poliza, $fecha_creacion, $nombre_solicitante)
    {
        $fecha = self::obtenerFecha($fecha_creacion);

        return $type_instrument . " NÚMERO " . self::validarDato($numero_poliza) . ", " . self::validarDato($fecha) .  ", 
    SE HIZO CONSTAR EL ACTO DE COTEJO SOLICITADO POR " . self::validarDato($nombre_solicitante) . ".";
    }


    /**
     * Genera el formato para Notificación.
     *
     * @param string $fecha_creacion Fecha en formato YYYY-MM-DD.
     * @param string $nombre_notificado Nombre de la persona o entidad notificada.
     * @param string $detalle_notificacion Descripción de la notificación.
     * @param string $nombre_solicitante Nombre del solicitante.
     * @param string $empresa_solicitante Empresa en representación de la cual se realiza la notificación.
     * @return string
     */
    public static function generateNotificacion($type_instrument, $numero_poliza, $fecha_creacion, $nombre_notificado, $detalle_notificacion,  $nombres_solicitantes)
    {
        $fecha = self::obtenerFecha($fecha_creacion);

        return $type_instrument . " NÚMERO " . self::validarDato($numero_poliza) . ", " . self::validarDato($fecha) . ", 
    SE HIZO CONSTAR LA NOTIFICACIÓN REALIZADA A " . self::validarDato($nombre_notificado) . " RESPECTO A " . self::validarDato($detalle_notificacion) . ", 
    A SOLICITUD DE " . self::validarDato($nombres_solicitantes) . ".";
    }


    /**
     * Genera el formato para Ratificación.
     *
     * @param string $fecha_creacion Fecha en formato YYYY-MM-DD.
     * @param string $documento_ratificado Nombre del documento ratificado.
     * @param string $nombre_otorgante Nombre de la persona que otorga la ratificación.
     * @param string $empresa Nombre de la empresa en representación de la cual se otorga la ratificación.
     * @return string
     */
    public static function generateRatificacion($type_instrument, $numero_poliza, $fecha_creacion, $documento_ratificado,  $nombres_solicitantes)
    {
        $fecha = self::obtenerFecha($fecha_creacion);

        return $type_instrument . " NÚMERO " . self::validarDato($numero_poliza) . ", " . self::validarDato($fecha). ", 
    SE HIZO CONSTAR LA RATIFICACIÓN DE FIRMAS " . self::validarDato($documento_ratificado) . " QUE OTORGAN 
    " . self::validarDato($nombres_solicitantes) . ".";
    }


    /**
     * Genera el formato para Fe de Hechos.
     *
     * @param string $fecha_creacion Fecha en formato YYYY-MM-DD.
     * @param string $detalle_hecho Descripción del hecho constatado.
     * @param string $nombre_solicitante Nombre del solicitante.
     * @param string $empresa Nombre de la empresa en representación de la cual se solicita la fe de hechos.
     * @return string
     */
    public static function generateFeDeHechos($type_instrument, $numero_poliza, $fecha_creacion, $detalle_hecho, $nombre_solicitante, $empresa)
    {
        $fecha = self::obtenerFecha($fecha_creacion);

        return $type_instrument . " NÚMERO " . self::validarDato($numero_poliza) . ", " . self::validarDato($fecha)  . ", 
    SE HIZO CONSTAR LA FE DE HECHOS RESPECTO A " . self::validarDato($detalle_hecho) . ", A SOLICITUD DE " . self::validarDato($nombre_solicitante) . " EN REPRESENTACIÓN DE " . self::validarDato($empresa) . ".";
    }

    // Designación
    /**
     * Genera el formato para Designación
     */

    // Designación
    public static function generateDesignacion($type_instrument, $fecha_creacion, $numero_poliza, $nombre_empresa, $tipo_sociedad, $nombres_solicitantes)
    {
        $fecha = self::obtenerFecha($fecha_creacion);
        return $type_instrument . " NÚMERO " . self::validarDato($numero_poliza) . ", " . self::validarDato($fecha) . ", 
        SE HIZO CONSTAR LA DESIGNACIÓN DE FUNCIONARIO DE \"" . self::validarDato($nombre_empresa) . "\" " . self::validarDato($tipo_sociedad) . ", 
        QUE OTORGA EL SEÑOR " . self::validarDato($nombres_solicitantes) . ", EN SU CARÁCTER DE ADMINISTRADOR ÚNICO DE LA SOCIEDAD.";
    }

    // Revocación
    /**
     * Genera el formato para Revocación
     */

    public static function generateRevocacion($type_instrument, $fecha_creacion, $numero_poliza, $nombre_empresa, $tipo_sociedad,  $nombres_solicitantes)
    {
        $fecha = self::obtenerFecha($fecha_creacion);

        return $type_instrument . " NÚMERO " . self::validarDato($numero_poliza) . ", " . self::validarDato($fecha) . ", 
        SE HIZO CONSTAR LA REVOCACIÓN DE FUNCIONARIO DE \"" . self::validarDato($nombre_empresa) . "\" " . self::validarDato($tipo_sociedad) . ", 
        QUE OTORGA EL SEÑOR " . self::validarDato($nombres_solicitantes) . ", EN SU CARÁCTER DE ADMINISTRADOR UNICO.";
    }





    // Formalización de Contrato / Convenio
    /**
     * Genera el formato para Formalización de Contrato o Convenio
     */

    public static function generateFormalizacionContrato($type_instrument, $fecha_creacion, $numero_poliza, $tipo_contrato, $de, $nombres_solicitantes)
    {
        $fecha = self::obtenerFecha($fecha_creacion);

        return $type_instrument . " NÚMERO " . self::validarDato($numero_poliza) . ", " . self::validarDato($fecha). ", 
        SE HIZO CONSTAR LA FORMALIZACIÓN DEL " . $tipo_contrato . " " . self::validarDato($de) . " CELEBRADO POR " . self::validarDato($nombres_solicitantes) . " ACTUANDO POR SU PROPIO DERECHO.";
    }

    // Compulsa
    /**
     * Genera el formato para Compulsa
     */
    public static function generateCompulsa($type_instrument, $fecha_creacion, $numero_poliza, $nombre_solicitante)
    {
        $fecha = self::obtenerFecha($fecha_creacion);

        return $type_instrument . " NÚMERO " . self::validarDato($numero_poliza) . ", " . self::validarDato($fecha) . ", 
        SE HIZO CONSTAR EL ACTO DE COMPULSA SOLICITADO POR " . self::validarDato($nombre_solicitante) . ".";
    }

    // Certificado de Depósitos de Acciones
    /**
     * Genera el formato para Certificado de Depósitos de Acciones
     */
    public static function generateCertificadoDepositoAcciones($type_instrument, $fecha_creacion, $numero_acta, $nombre_solicitante)
    {
        $fecha = self::obtenerFecha($fecha_creacion);

        return $type_instrument . " NÚMERO " . self::validarDato($numero_acta) . ", " . self::validarDato($fecha) . ", 
        SE HIZO CONSTAR EL ACTO DE CERTIFICADO DE DEPÓSITO DE ACCIONES SOLICITADO POR " . self::validarDato($nombre_solicitante) . ".";
    }


    // Declaraciones Mercantiles
    /**
     * Genera el formato para Declaraciones Mercantiles
     * 
     * @param string $numero_acta
     * @param int $dia
     * @param string $mes
     * @param int $anio
     * @param string $detalle_declaracion
     * @param string $nombre_solicitante
     * @param string $empresa
     * @return string
     */
    public static function generateDeclaracionesMercantiles($type_instrument, $fecha_creacion, $numero_acta, $detalle_declaracion, $nombre_solicitante)
    {
        $fecha = self::obtenerFecha($fecha_creacion);

        return $type_instrument . " NÚMERO " . self::validarDato($numero_acta) . ", " . self::validarDato($fecha) . ", 
        SE HIZO CONSTAR LAS DECLARACIONES MERCANTILES RESPECTO " . self::validarDato($detalle_declaracion) . ", A SOLICITUD DE " . self::validarDato($nombre_solicitante) . ".";
    }

    // Comisión Mercantil
    /**
     * Genera el formato para Comisión Mercantil
     * 
     * @param string $numero_poliza
     * @param int $dia
     * @param string $mes
     * @param int $anio
     * @param string $nombre_otorgante
     * @param string $empresa_otorgante
     * @param string $nombre_beneficiario
     * @param string $empresa_beneficiaria
     * @return string
     */
    public static function generateComisionMercantil($type_instrument, $fecha_creacion, $numero_poliza, $nombres_solicitantes, $in_favor_of)
    {
        $fecha = self::obtenerFecha($fecha_creacion);

        return $type_instrument . " NÚMERO " . self::validarDato($numero_poliza) . ", " . self::validarDato($fecha) . ", 
        SE HIZO CONSTAR EL \"MANDATO\" APLICADO A ACTOS DE COMERCIO CONOCIDO COMO COMISIÓN MERCANTIL, 
        QUE OTORGAN EL/LOS SEÑOR(ES) " . self::validarDato($nombres_solicitantes) . ", A FAVOR DEL SEÑOR " . $in_favor_of . ".";
    }


    public static function generateDefault($type_instrument, $fecha_creacion, $numero_acta, $nombres_solicitantes, $act_title)
    {
        $fecha = self::obtenerFecha($fecha_creacion);

        return $type_instrument . " NÚMERO " . self::validarDato($numero_acta) . ", " . self::validarDato($fecha) . ", 
        SE HIZO CONSTAR EL ACTO DE " . $act_title . " SOLICITADO POR " . self::validarDato($nombres_solicitantes) . ".";
    }
}
