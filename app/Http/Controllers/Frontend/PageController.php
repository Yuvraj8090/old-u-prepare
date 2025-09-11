<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\DummyData;
use App\Models\Admin\Page;
use App\Models\Admin\Announcement;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

/**
 * Website Pages Routes Controller
 *
 * @author Robin Tomar <robintomr@icloud.com>
 */

class PageController extends Controller
{
    /**
     *
     */
    public function index()
    {
        $videos     = DummyData::videos();
        $slides     = DummyData::slidesData();
        $persons    = DummyData::honpData();
        $cc_items   = DummyData::citizenCornerData();
        $pps_items  = DummyData::pastProjectsData();
        // $components = DummyData::compsData();

        $announcements = Announcement::where('show', 1)->get(['hin_title', 'eng_title', 'id', 'slug']);
        $components    = Page::where('parent_menu', 3)->orderBy('id', 'desc')->get(['slug', 'eng_content', 'hin_content', 'page_eng_title', 'page_hin_title', 'image']);

        // dd($components);

        return view('public.page.welcome', compact('slides', 'videos', 'persons', 'cc_items', 'pps_items', 'components','announcements'));
    }


    /**
     *
     */
    public function about()
    {
        return view('public.page.menu.about.index');
    }


    /**
     *
     */
    public function contact()
    {
        return view('public.page.menu.contact');
    }


    /**
     *
     */
    public function mission()
    {
        return view('public.page.menu.about.mission');
    }


    /**
     *
     */
    public function history()
    {
        return view('public.page.menu.about.history');
    }


    /**
     *
     */
    public function objective()
    {
        return view('public.page.menu.about.objective');
    }


    /**
     *
     */
    public function structure()
    {
        return view('public.page.menu.about.structure');
    }


    /**
     *
     */
    public function team()
    {
        return view('public.page.menu.about.team');
    }


    /**
     *
     */
    public function announcement($slug)
    {
        $announcement = Announcement::where('slug', $slug)->first();

        return view('public.page.announcement.index',compact('announcement'));
    }


    /**
     *
     */
    public function enhancingInfra()
    {
        return view('public.page.menu.component.enhancinginfra');
    }


    /**
     *
     */
    public function improvingEmergency()
    {
        return view('public.page.menu.component.improvingemergency');
    }


    /**
     *
     */
    public function managingForestFire()
    {
        return view('public.page.menu.component.forestfire');
    }


    /**
     *
     */
    public function projectManagement()
    {
        return view('public.page.menu.component.projectmanagement');
    }


    /**
     *
     */
    public function contingentEmergency()
    {
        return view('public.page.menu.component.contingentemergency');
    }


    /**
     *
     */
    public function page($slug)
    {
        $data = Page::where('slug', $slug)->first();

        if($data)
        {
            return view('public.page.menu.component.index', compact('data'));
        }

        $pages = ['our-team', 'history', 'about-us', 'contact-us', 'objectives', 'mission-and-vision', 'tenders-and-notices', 'project-structure', 'project-management', 'enhancing-infrastructure-resilience', 'contingent-emergency-response-component', 'improving-emergency-preparedness-and-response', 'preventing-and-managing-forest-and-general-fires'];

        $views = [
            'history'                                         => 'public.page.menu.about.history',
            'our-team'                                        => 'public.page.menu.about.team',
            'about-us'                                        => 'public.page.menu.about.index',
            'contact-us'                                      => 'public.page.menu.contact',
            'objectives'                                      => 'public.page.menu.about.objective',
            'project-structure'                               => 'public.page.menu.about.structure',
            'mission-and-vision'                              => 'public.page.menu.about.mission',
            'project-management'                              => 'public.page.menu.component.projectmanagement',
            'tenders-and-notices'                             => 'public.page.menu.resource.tender',
            'enhancing-infrastructure-resilience'             => 'public.page.menu.component.enhancinginfra',
            'contingent-emergency-response-component'         => 'public.page.menu.component.contingentemergency',
            'improving-emergency-preparedness-and-response'   => 'public.page.menu.component.improvingemergency',
            'preventing-and-managing-forest-and-general-fires'=> 'public.page.menu.component.forestfire'
        ];

        if(in_array($slug, $pages))
        {
            return view($views[$slug]);
        }

        abort(404);
    }
}
