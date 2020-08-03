<?php
namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\User;

class TestComposer
{
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {

        $contact = 'Yes This is Our Contact';

        $contact_info = 'This is the info part';

        $view->with('contact', $contact)->with('contact_info', $contact_info);
    }
}
