<?php

const DEFAULT_PAGINATE_PER_PAGE = 10;
const DEFAULT_ORDER_BY_COLUMN = "id";
const DEFAULT_ASCENDING = "asc";
const SECONDS = "1000";

if (! function_exists('mask'))
{
    function mask($val, $mask)
    {
        $maskared = '';
        $k = 0;
        for($i = 0; $i<=strlen($mask)-1; $i++)
        {
            if($mask[$i] == '#')
            {
                if(isset($val[$k]))
                    $maskared .= $val[$k++];
            }
            else
            {
                if(isset($mask[$i]))
                    $maskared .= $mask[$i];
            }
        }
        return $maskared;
    }
}

if (! function_exists('months'))
{
    function months()
    {
        return [
            1 => 'Jan',
            2 => 'Fev',
            3 => 'Mar',
            4 => 'Abr',
            5 => 'Mai',
            6 => 'Jun',
            7 => 'Jul',
            8 => 'Ago',
            9 => 'Set',
            10 => 'Out',
            11 => 'Nov',
            12 => 'Dez'
        ];
    }
}

if (! function_exists('daysOfWeek'))
{
    function daysOfWeek()
    {
        return ['1' => 'Domingo',
        '2' => 'Segunda-Feira',
        '3' => 'Terca-Feira',
        '4' => 'Quarta-Feira',
        '5' => 'Quinta-Feira',
        '6' => 'Sexta-Feira',
        '7' => 'Sábado'];
    }
}

if (! function_exists("defaultSaveMessagemNotification"))
{
    function defaultSaveMessagemNotification()
    {
        return array(
            'message' => __('Dados salvos com sucesso!'),
            'alert-type' => 'success'
        );
    }
}

if (! function_exists("setNotification"))
{
    function setNotification($msg, $type = "success")
    {
        return array(
            'message' => $msg,
            'alert-type' => $type
        );
    }
}

if (! function_exists('flash'))
{
    function flash($message, $type = "success")
    {
        Session::flash('message', __($message));
        Session::flash('alert-type', $type);
    }
}
