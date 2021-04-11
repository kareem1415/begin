<?php

namespace App\Http\Controllers;

use App\Events\VideoViewer;
use App\Http\Requests\OfferRequest;
use App\Models\Offer;
use App\Models\Video;
use App\Scopes\OfferScope;
use App\Traits\OfferTrait;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use LaravelLocalization;

class CrudController extends Controller
{
    use OfferTrait;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function getOffers(){

        return Offer::select('id', 'name')->get();
    }

    /*public function store(){
        Offer::create([
            'name' => 'Offer3',
            'price' => '500',
            'details' => 'offer detail',
        ]);
    }*/

    public function create()
    {
        return view('offers.create');
    }

    public function store(OfferRequest $request)
    {
        // validate data before insert to database

//        $rules = $this->getRules();
//        $messages = $this->getMessages();
//        $validator = Validator::make($request->all(),$rules, $messages);
//
//        if ($validator -> fails()){
//            return redirect()->back()->withErrors($validator)->withInputs($request->all());
//        }



        // this method use for save photo
        $file_name = $this->saveImage($request -> photo , 'images/offers');
        // insert

        Offer::create([
            'photo' => $file_name,
            'name_ar' => $request->name_ar,
            'name_en' => $request->name_en,
            'price' => $request->price,
            'details_ar' => $request->details_ar,
            'details_en' => $request->details_en,
        ]);

        return redirect()->back()->with(['success' => 'تم اضافة العرض بنجاح']);
    }


/*    protected function getRules()
    {
        return $rules = [
            'name' => 'required|max:100|unique:offers,name',
            'price' => 'required|numeric',
            'details' => 'required',
        ];
    }*/

/*    protected function getMessages()
    {
        return $messages = [
            'name.required' =>__('messages.offer name required'),
            'name.unique' =>__('messages.offer name must be unique'),
            'price.numeric' => 'سعر العرض يجب ان يكون ارقام',
            'price.required' => 'السعر مطلوب',
            'details.required' => 'التفاصيل مطلوبه',
        ];
    }*/
    public function getAllOffers()
    {
/*        $offers = Offer::select('id',
            'price',
            'name_' . LaravelLocalization::getCurrentLocale() . ' as name',
            'details_' . LaravelLocalization::getCurrentLocale() . ' as details'
        )->get(); // return collection of all result*/

        ########################### paginate result ################################

        $offers = Offer::select('id',
            'price',
            'name_' . LaravelLocalization::getCurrentLocale() . ' as name',
            'details_' . LaravelLocalization::getCurrentLocale() . ' as details'
        )->paginate(PAGINATION_COUNT);

         // return view('offers.all', compact('offers'));

        return view('offers.paginations', compact('offers'));
    }

    public function editOffer($offer_id)
    {
//        Offer::findOrFail($offer_id);
        $offer = Offer::find($offer_id);

        if (!$offer)
        return redirect() -> back();

        $offer = Offer::select('id', 'name_ar', 'name_en', 'details_ar', 'details_en', 'price')->find($offer_id);

        return view('offers.edit', compact('offer'));
    }

    public function delete($offer_id)
    {
        // check if offer id exists

        $offer = Offer::find($offer_id); // Offer::where('id', '$offer_id') -> first();

        if(!$offer)
            return redirect() -> back() ->with(['error' => __('massages.offer not exist')]);

        $offer -> delete();

        return redirect()->route('offers.all', $offer_id) ->with(['success'=>__('messages.offer deleted successfully')]);
    }

    public function updateOffer(OfferRequest $request, $offer_id)
    {
        // validtion

        // check if offer exists

        $offer = Offer::find($offer_id);
        if (!$offer) {
            return redirect()->back();
        }
        // update data
        else {
            $offer->update($request->all());

            return redirect()->back()->with(['success' => ' تم التحديث بنجاح ']);
        }
        /* $offer->update([
                'name_ar' => $request->name_ar,
                'name_en' => $request->name_en,
                'price' => $request->price,
        */
    }

    public function getVideo()
    {
        $video = Video::first();
        event(new VideoViewer($video)); // fire event
        return view('video') -> with('video', $video);

    }

    public function getAllInactiveOffers()
    {
        // where whereNull whereNotNull whereIn
        // Offer::whereNotNull('details_ar')->get();

        // return $inactiveOffers = Offer::invalid()->get();   // all in active offers

        // global scope
       // return $inactiveOffers = Offer::get();

        // how to remove global scope

        return $offer = Offer:: withoutGlobalScope(OfferScope::class)->get();
    }
}
