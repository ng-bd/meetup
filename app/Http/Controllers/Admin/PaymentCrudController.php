<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AttendeeRequest;
use App\Jobs\SendEmailJob;
use App\Mail\PaymentSuccess;
use App\Models\Attendee;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class AttendeeCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class PaymentCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\Payment');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/payment');
        $this->crud->setEntityNameStrings('payment', 'payments');
//        $this->crud->addButtonFromModelFunction('line', 'print', 'print');
        $this->crud->denyAccess(['create', 'update', 'delete']);

        $this->crud->enableExportButtons();
    }

    protected function setupListOperation()
    {
        $this->crud->addColumns([
            [
                'name' => 'id',
                'type' => 'text',
                'label' => '# ID'
            ],
            [
                'name' => 'card_type',
                'type' => 'text',
                'label' => 'Card Type'
            ],
            [
                'name' => 'transaction_id',
                'type' => 'text',
                'label' => 'Transaction Id'
            ],
            [
                'name' => 'amount',
                'type' => 'text',
                'label' => 'Amount'
            ],
            [
                'name' => 'attendee_id',
                'type' => 'select',
                'label' => 'Attendee',
                'entity' => 'attendee',
                'attribute' => 'name',
                'model' => Attendee::class
            ],
            [
                'name' => 'mobile_number',
                'type' => 'select',
                'label' => 'Attendee Phone Number',
                'entity' => 'attendee',
                'attribute' => 'mobile',
                'model' => Attendee::class
            ],
            [
                'name' => 'email',
                'type' => 'select',
                'label' => 'Attendee Email',
                'entity' => 'attendee',
                'attribute' => 'email',
                'model' => Attendee::class
            ],
            [
                'name' => 'api_response',
                'type' => 'text',
                'label' => 'Api Response'
            ]


        ]);

    }

    protected function setupShowOperation()
    {
        $this->setupListOperation();
    }
}
