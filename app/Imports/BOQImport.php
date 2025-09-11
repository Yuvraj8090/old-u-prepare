<?php

namespace App\Imports;

use App\Models\PWDBOQ;
use App\Models\Contracts;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class BOQImport implements ToCollection
{
    /*
     *
     */
    private $contract;


    /**
     *
     */
    public function __construct(Contracts $contract)
    {
        $this->contract = $contract;
    }


    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        foreach($collection as $key => $row)
        {
            $boq_data = (object) [
                'qty'        => 0,
                's_no'       => NULL,
                'item'       => NULL,
                'unit'       => NULL,
                'rate'       => 0,
                'title'      => 0,
                'heading'    => 0,
                'section'    => 0,
                'contract_id'=> $this->contract->id,
            ];

            // Start Parsing from 5th row of excel (As defined)
            if($key > 4)
            {
                $boq_data->item  = $row[1];

                if($this->isTitle($row))
                {
                    $boq_data->item  = $row[0];
                    $boq_data->title = 1;
                }
                elseif($this->isSection($row))
                {
                    $boq_data->s_no    = !empty($row[0]) ? $row[0] : NULL;
                    $boq_data->section = 1;
                }
                elseif($this->isData($row))
                {
                    $boq_data->qty  = $row[3];
                    $boq_data->rate = $row[4];
                    $boq_data->unit = $row[2];
                    $boq_data->s_no = !empty($row[0]) ? $row[0] : NULL;
                }
                elseif($this->isHeading($row))
                {
                    $boq_data->heading = 1;
                }

                if(trim($row[0]) == 'Total')
                {
                    break;
                }

                PWDBOQ::create((array) $boq_data);
            }
        }

        return TRUE;
    }


    /**
     * Check if the row contains title
     */
    private function isTitle($data)
    {
        $is_title_row = false;

        if(empty($data[1]) && empty($data[2]) && empty($data[3]) && empty($data[4]))
        {
            $is_title_row = true;
        }

        return $is_title_row;
    }


    /**
     *
     */
    private function isSection($data)
    {
        $is_section = false;

        if(!empty($data[0]) && !empty($data[1]) && empty($data[2]) && empty($data[3]) && empty($data[4]))
        {
            $is_section = true;
        }

        return $is_section;
    }


    /**
     *
     */
    private function isHeading($data)
    {
        $is_heading = false;

        if(empty($data[0]) && !empty($data[1]) && empty($data[2]) && empty($data[3]) && empty($data[4]))
        {
            $is_heading = true;
        }

        return $is_heading;
    }


    /**
     *
     */
    private function isData($data)
    {
        $is_data = false;

        if(!empty($data[1]) && !empty($data[2]) && !empty($data[3]) && !empty($data[4]))
        {
            $is_data = true;
        }

        return $is_data;
    }
}
