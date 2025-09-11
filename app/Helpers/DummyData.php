<?php

namespace App\Helpers;


/**
 * Static Dummy Data for Website
 *
 * @author Robin Tomar <robintomr@icloud.com>
 */

class DummyData
{
    public static function slidesData()
    {
        return [
            (object) [
                'img'     => 'assets/img/slider/simg_01.webp',
                'head'    => 'Resilient Uttarakhand: Safeguarding Lives and Landscapes',
                'subh'    => 'Nestled amidst the majestic Himalayas, the Uttarakhand Disaster Management Authority stands vigilant, committed to protecting our communities and preserving our natural heritage',
                'link'    => NULL,
                'btn_text'=> NULL
            ],
            (object) [
                'img'     => 'assets/img/slider/simg_02.webp',
                'head'    => 'Building Resilient Infrastructure',
                'subh'    => 'Fortifying foundations for a sustainable tomorrow. Strengthening structures and systems to withstand the test of time and nature.',
                'link'    => NULL,
                'btn_text'=> NULL
            ],
            (object) [
                'img'     => 'assets/img/slider/simg_03.webp',
                'head'    => 'Elevating Emergency Preparedness and Response',
                'subh'    => 'Ready, resilient, responsive. Equipping communities with the tools and knowledge to face emergencies head-on, ensuring safety and swift action.',
                'link'    => NULL,
                'btn_text'=> NULL
            ],
            (object) [
                'img'     => 'assets/img/slider/simg_04.webp',
                'head'    => 'Safeguarding Against Forest and General Fires',
                'subh'    => 'Guardians of green, protectors of progress. Combating fire hazards through vigilant prevention and effective management, safeguarding our natural and built environments.',
                'link'    => NULL,
                'btn_text'=> NULL
            ],
            (object) [
                'img'     => 'assets/img/slider/simg_05.webp',
                'head'    => 'Excellence in Project Management',
                'subh'    => 'Strategizing success, executing with precision. Managing projects efficiently from inception to completion, ensuring impactful and sustainable outcomes.',
                'link'    => NULL,
                'btn_text'=> NULL
            ],
        ];
    }


    /**
     *
     */
    public static function compsData()
    {
        return [
            (object) [
                'img'=> 'assets/img/comps/width_800.jpeg',
                'head'=> 'Enhancing Infrastructure Resilience',
                'desc'=> 'This initiative strengthens infrastructure against climate and disaster risks by enhancing road resilience, upgrading health facilities for safety, and building energy-efficient disaster shelters. Key actions include reinforcing bridges, mitigating landslides with bioengineering, and retrofitting health centers. These efforts ensure better protection against environmental hazards in Uttarakhand.',
                'link'=> '#',
            ],
            (object) [
                'img'=> 'assets/img/comps/width_610.webp',
                'head'=> 'Improving Emergency Preparedness and Response',
                'desc'=> 'Enhances disaster prediction, preparedness, and response with a State Emergency Operations Centre, improved multi-agency coordination, community DRM campaigns, upgraded hydromet systems for accuracy, timely alerts, strengthened State Disaster Response Force with training facilities, equipment, and comprehensive programs.',
                'link'=> '',
            ],
            (object) [
                'img'=> 'assets/img/comps/width_480.webp',
                'head'=> 'Preventing and Managing Forest and General Fires',
                'desc'=> 'Enhancing fire prevention and management includes fire risk assessments, state-level plans, advanced technologies, and community-based initiatives. Efforts focus on early detection, policy reviews, equipment procurement, and sustainable forest management incentives. These measures mitigate fire risks, promote biodiversity, and ensure long-term sustainability.',
                'link'=> '',
            ],
            (object) [
                'img'=> 'assets/img/comps/width_800_b.jpeg',
                'head'=> 'Project Management',
                'desc'=> 'Facilitates project management and knowledge sharing through financial management, procurement, environmental and social management, communication, monitoring, evaluation, and stakeholder engagement. Establishes Lighthouse Uttarakhand for sharing project lessons, capacity building, and knowledge exchange among Indian states.',
                'link'=> '',
            ],
        ];
    }


    /**
     *
     */
    public static function honpData()
    {
        return [
            (object) ['img'=> 'cm_shri-pushkar-singh-dhami.webp', 'name'=> 'SHRI PUSHKAR SINGH DHAMI', 'title'=> 'Hon\'ble Chief Minister, Uttarakhand'],
            (object) ['img'=> 'governor_shri-gurmit-singh.webp', 'name'=> 'SHRI GURMIT SINGH', 'title'=> 'Hon\'ble Governor, Uttarakhand'],
            (object) ['img'=> 'chief-secretary_shri-anand-bardhan.jpg', 'name'=> 'SHRI ANAND BARDHAN', 'title'=> 'Chief Secretary'],
            (object) ['img'=> 'secretary-dm_shri-vinod-kumar-suman.webp', 'name'=> 'SHRI. VINOD KUMAR SUMAN', 'title'=> 'Secretary, Disaster Management'],
        ];
    }


    /**
     *
     */
    public static function citizenCornerData()
    {
        return [
            (object) ['img'=> 'project-status.webp', 'name'=> 'Projects Status', 'link'=> '#'],
            (object) ['img'=> 'tenders.webp', 'name'=> 'Tenders & Notice', 'link'=> route('public.page.tenders')],
            (object) ['img'=> 'grievance-register.webp', 'name'=> 'Grievance Register', 'link'=> route('public.grievance.register')],
            (object) ['img'=> 'grievance-status.webp', 'name'=> 'Grievance Status', 'link'=> route('public.grievance.status')],
            (object) ['img'=> 'vacancies.webp', 'name'=> 'Vacancies', 'link'=> '#'],
            (object) ['img'=> 'suggestions.webp', 'name'=> 'Suggestions', 'link'=> '#'],
        ];
    }


    /**
     *
     */
    public static function pastProjectsData()
    {
        return [
            (object) ['img'=> 'assets/img/pps-bgi.webp', 'bgc'=> 'udrp', 'title'=> 'UDRP: Uttarakhand Disaster Recovery Project (2014-2019)', 'name'=> 'Past Projects', 'link'=> route('public.page.history'), 'link_txt'=> 'Learn More'],
            (object) ['img'=> 'assets/img/pps-bgi-af.webp', 'bgc'=> 'udrpaf', 'title'=> 'UDRP-AF: Uttarakhand Disaster Recovery Project - AF (2019-2023)', 'name'=> 'Past Projects', 'link'=> route('public.page.history'), 'link_txt'=> 'Learn More'],
        ];
    }


    /**
     *
     */
    public static function videos()
    {
        return [
            (object) [
                'img'=> 'assets/public/img/videos/width_200b.webp',
                'text'=> '6th World Congress_Lieutenant General Gurmit Singh Hon\'ble Governor Govt of Uttarakhand'
            ],
            (object) [
                'img'=> 'assets/public/img/videos/width_200.webp',
                'text'=> '6th World Congress on Disaster Management Dehradun 28th Nov to 1st Dec 2023_Shri Amitabh Bachchan@1'
            ],
            (object) [
                'img'=> 'assets/public/img/videos/width_200c.webp',
                'text'=> '6th World Congress on Disaster Management Dehradun_ Dr Ranjit Kumar Sinha, Secretary_4, USDMA'
            ],
            (object) [
                'img'=> 'assets/public/img/videos/width_200d.webp',
                'text'=> 'Uttarakhand State Disaster Management Authority Live Stream'
            ],
        ];
    }


    /**
     *
     */
    public static function typology($slug = NULL, $item = NULL)
    {
        $typologies = [
            (object) ['name'=> 'Bridges', 'slug'=> 'bridges'],
            (object) ['name'=> 'Road', 'slug'=> 'road'],
            (object) ['name'=> 'Slope Protection', 'slug'=> 'slope-protection'],
            (object) ['name'=> 'Forest Fire Management', 'slug'=> 'forest-fire-management'],
            (object) ['name'=> 'Construction of Buildings/Fire Stations', 'slug'=> 'construction-of-buildings-or-fire-stations'],
            (object) ['name'=> 'Other', 'slug'=> 'other'],
        ];

        $typologies = [
            (object) ['name'=> 'Bridges & Approach Road (PWD)', 'slug'=> 'bridges-and-approach-road-pwd', 'dept'=> 'PWD'],
            (object) ['name'=> 'Slope Protection (PWD)', 'slug'=> 'slope-protection-pwd', 'dept'=> 'PWD'],
            (object) ['name'=> 'Construction of building/fire stations/ fire training centre (RWD)', 'slug'=> 'construction-of-building-fire-stations-fire-training-centre-rwd', 'dept'=> 'RWD'],
            (object) ['name'=> 'Forest Fire Management (Forest/USDMA)', 'slug'=> 'forest-fire-management-forest-usdma', 'dept'=> 'Forest'],
            (object) ['name'=> 'Other', 'slug'=> 'other'],
        ];

        if($slug)
        {
            $row  = NULL;
            $name = NULL;

            foreach($typologies as $typology)
            {
                if($typology->slug == $slug)
                {
                    $row = $typology;
                    $name = $typology->name;
                    break;
                }
            }

            return $item ? $row : $name;
        }

        return $typologies;
    }


    /**
     *
     */
    public static function months($get = NULL)
    {
        $months = [
            'January', 'February', 'March',
            'April', 'May', 'June', 'July',
            'August', 'September', 'October',
            'November', 'December'
        ];

        if(!is_null($get) && isset($months[$get - 1]))
        {
            return $months[$get - 1];
        }

        return $months;
    }
}
