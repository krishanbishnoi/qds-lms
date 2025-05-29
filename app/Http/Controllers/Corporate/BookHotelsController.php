<?php

namespace App\Http\Controllers\Corporate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\BookingManager;
use App\Models\Hotel;
use App\Models\Employees;
use App\Models\City;
use App\Models\States;
use App\Models\HotelRating;
use App\Models\Amenity;
use DB;
use Str;
use Crypt;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class BookHotelsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (isset($request->city) && !empty($request->city)) {
            $city = $request->city;
            $hotelDb = Hotel::select('id', 'title', 'description', 'address_1', 'address_2', 'country', 'state', 'city', 'primary_image', 'status')->where(['city' => $city, 'status' => 1])->get()->toArray();
        } else {
            $city = 'Delhi';
            $hotelDb = Hotel::select('id', 'title', 'description', 'address_1', 'address_2', 'country', 'state', 'city', 'primary_image', 'status')->where('status', '=', 1)->get()->toArray();
        }
        $apicityid = City::where('name', '=', $city)->first();
        if (!empty($apicityid)) {
            $hotelArrayApi = app('App\Http\Controllers\Admin\HotelController')->hotelApi('GetHotelResult', $apicityid->cityid);
        } else {
            $hotelArrayApi = app('App\Http\Controllers\Admin\HotelController')->hotelApi('GetHotelResult', '130443');
        }

        if (!empty($hotelArrayApi)) {
            $HotelResultAll = [];
            foreach ($hotelArrayApi as $key => $val) {
                $HotelResults = [];
                $HotelResults['ResultIndex'] = $val->ResultIndex;
                $HotelResults['HotelCode'] = $val->HotelCode;
                $HotelResults['title'] = $val->HotelName;
                $HotelResults['description'] = $val->HotelDescription;
                $HotelResults['address_1'] = $val->HotelAddress;
                $HotelResults['address_2'] = '';
                $HotelResults['country'] = 'india';
                $HotelResults['state'] = '';
                $HotelResults['city'] = '';
                $HotelResults['primary_image'] = '';
                $HotelResults['hotel_image'] = $val->HotelPicture;
                $HotelResultAll[] = $HotelResults;
            }
            $hotels = array_merge($hotelDb, $HotelResultAll);

        } else {
            $hotels = $hotelDb;
        }

        $hotelList = [];
        foreach ($hotels as $Hkey => $hotel) {
            if (!array_key_exists('id', $hotel))
                continue;
            if (!Hotel::find($hotel['id']))
                continue;

            $HotelRating = HotelRating::where('hotel', '=', $hotel['id'])->get()->toArray();
            $AvgHotelRating = 0;
            if ($HotelRating) {
                $AvgHotelRating = array_sum(array_column($HotelRating, 'rating')) / count($HotelRating);
            }
            $hotel['id'] = Crypt::encrypt($hotel['id']);
            $hotel['rating'] = $AvgHotelRating;
            $hotel['beds'] = DB::table('hotel_room_management')->where('hotel', '=', $hotel['id'])->get()->count();
            $hotel['guests'] = DB::table('hotel_room_management')->where('hotel', '=', $hotel['id'])->sum('occupancy');
            $hotel['rooms'] = DB::table('hotel_room_management')->where('hotel', '=', $hotel['id'])->orderBy('r_price', 'DESC')->get()->toArray();
            $hotelList[$Hkey] = $hotel;
        }
        // dd($hotelList);
        $States = States::where('country_id', '=', 101)->get();
        $allAmenities = $this->hotelAllAmenities();
        // dd($allAmenities);
        return view('corporate.book-hotel.book-hotel', compact('hotelList', 'States', 'allAmenities'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function bookHotelDetail(Request $request)
    {
        // dd(Crypt::decrypt($request->hotel));
        $hotelID = Crypt::decrypt($request->hotel);
        if (!Hotel::find($hotelID))abort(404);
        $HotelInfo = Hotel::find($hotelID)->toArray();
        $HotelRating = HotelRating::where('hotel', '=', $hotelID)->get()->toArray();
        $AvgHotelRating = 0;
        if ($HotelRating) {
            $AvgHotelRating = array_sum(array_column($HotelRating, 'rating')) / count($HotelRating);
        }
        $HotelInfo['rating'] = $AvgHotelRating;

        $HotelRooms = DB::table('hotel_room_management')->where('hotel', '=', $hotelID)->get()->toArray();
        $Amenities = $this->hotelAllAmenities($hotelID);

        $similarHotelsArr = Hotel::where('id', '!=', $hotelID)->get()->toArray();
        $similarHotels = [];
        foreach ($similarHotelsArr as $rmkey => $similarHotelArr) {
            $similarHotels[$rmkey ] = $similarHotelArr;
            $similarHotelRooms = DB::table('hotel_room_management')->where('hotel', '=', $similarHotelArr['id'])->get()->toArray();
            $startPrice = 0;
            foreach ($similarHotelRooms as $key => $similarHotelRoom) {
                $similarHotelRoomPrice = $similarHotelRoom->s_price && $similarHotelRoom->s_price < $similarHotelRoom->r_price?$similarHotelRoom->s_price:$similarHotelRoom['r_price'];
                if($startPrice == 0 || $similarHotelRoomPrice < $startPrice){
                    $startPrice = $similarHotelRoomPrice;
                }
            }
            if($startPrice <= 0){
                unset($similarHotels[$rmkey]);
                continue;
            }
            $similarHotels[$rmkey]['startPrice'] = $startPrice;
        }
        $employeesList = Employees::select('id', 'first_name', 'last_name', 'emp_email', 'phone_number')->where('added_by', $hotelID)->get()->toArray();
        // dd($HotelInfo);
        return view('corporate.book-hotel.book-hotel-detail', compact('HotelInfo', 'HotelRooms', 'Amenities', 'similarHotels', 'employeesList'));
    }

    public function HotelBookingsList()
    {
        $CompletedBookingsList = BookingManager::where(['hotelId' => Auth::user()->id, 'status' => 'checked-in'])->get()->toArray();
        $UpComingBookingsList = BookingManager::where(['booking_manager.hotelId' => Auth::user()->id, 'booking_manager.status' => 'pending'])
            ->join('hotels', 'hotels.id', '=', 'booking_manager.hotelId')
            ->join('hotel_room_management', 'hotel_room_management.id', '=', 'booking_manager.roomId')->get()->toArray();
        $SavedBookingsList = BookingManager::where(['hotelId' => Auth::user()->id, 'status' => 'checked-out'])->get()->toArray();
        // dd($UpComingBookingsList);
        return view('corporate.bookings.bookings', compact('CompletedBookingsList', 'UpComingBookingsList', 'SavedBookingsList'));
    }

    public function BookableHotelInfo(Request $request)
    {
        // $request->validator(['id'=>'required']);
        $hotelID = Crypt::decrypt($request->id);
        if (!$hotelID)
            return response()->json(['status' => false, 'message' => 'Hotel ID Not Found.']);
        $hotelInfo = Hotel::select('id', 'title', 'description', 'address_1', 'address_2', 'country', 'state', 'city', 'primary_image', 'status')->where('status', '=', 1)->find($hotelID)->toArray();
        $BookableHotelInfo = [];
        if ($hotelInfo) {
            $hotelInfo['id'] = Crypt::encrypt($hotelInfo['id']);
            $BookableHotelInfo['hotel_info'] = $hotelInfo;
            $HotelRating = HotelRating::where('hotel', '=', $hotelID)->get()->toArray();
            $AvgHotelRating = 0;
            if ($HotelRating) {
                $AvgHotelRating = array_sum(array_column($HotelRating, 'rating')) / count($HotelRating);
            }
            $BookableHotelInfo['hotel_info']['rating'] = $AvgHotelRating;
            $BookableHotelInfo['hotel_info']['rooms'] = DB::table('hotel_room_management')->where('hotel', '=', $hotelID)->get()->toArray();
            return response()->json(['status' => false, 'message' => 'Hotel Details.', 'data' => $BookableHotelInfo]);
        } else {
            return response()->json(['status' => false, 'message' => 'Hotel Not Found.']);
        }
    }

    public function hotelAllAmenities($hotelID = false, $type = 'all')
    {

        $roomIncludes = true;
        $commonIncludes = true;
        $allAmenities = [];
        $allAmenitiesSlug = [];

        if (!$hotelID) {
            $allAmenities = Amenity::select('id', 'title')->get()->toArray() ?? [];
            return $allAmenities;
        }

        if ($type == 'common') {
            $roomIncludes = false;
        }
        if ($type == 'rooms') {
            $commonIncludes = false;
        }

        $HotelInfo = Hotel::find($hotelID)->toArray();
        $hotelRooms = DB::table('hotel_room_management')->where('hotel', '=', $hotelID)->get()->toArray();
        $commonAmenity = $HotelInfo['common_amenity'] ? explode(',', $HotelInfo['common_amenity']) : [];

        if ($commonIncludes) {
            foreach ($commonAmenity as $key => $amenitie) {
                if (!in_array($amenitie, $allAmenitiesSlug)) {
                    $allAmenitiesSlug[] = $amenitie;
                    $allAmenities[] = Amenity::where('slug', $amenitie)->first()->toArray();
                }
            }
        }
        if ($roomIncludes) {
            $roomAmenities = array_column($hotelRooms, 'amenity');
            if ($roomAmenities) {
                foreach ($roomAmenities as $key => $roomAmenitie) {
                    $roomAmenitie = explode(',', $roomAmenitie);
                    foreach ($roomAmenities as $key => $amenitie) {
                        if (!in_array($amenitie, $allAmenitiesSlug)) {
                            $allAmenitiesSlug[] = $amenitie;
                            $allAmenities[] = Amenity::where('slug', $amenitie)->first()->toArray();
                        }
                    }
                }
            }
        }

        return $allAmenities;
    }

    public function bookingSummary(Request $request)
    {

        $roomTypes = [];
        $noOfTotlaRooms = 0;
        $membersArr = $bookingSummary = [];
        $hotelId = $request->hotelId;
        $totalDay = 2;

        $BookingRooms = array_filter($request->booking['room-type'], function ($rooms) {
            return $rooms['noOfroom'] != 0 && array_key_exists('room',$rooms);
        });
        $i = 0;
        $roomTypePrice = 0;
        $totalDayPrice = 0;
        foreach ($BookingRooms as $key => $roomInfo) {
            $roomtypeInfo = DB::table('hotel_room_management')->where(['hotel' => $hotelId, 'id' => $key])->first();
            $roomTypes[$i]['typeId'] = $key;
            $roomTypes[$i]['title'] = Str::ucfirst(str_replace('-',' ',$roomtypeInfo->room_type));
            $roomTypes[$i]['price'] = ($roomtypeInfo->s_price && $roomtypeInfo->s_price < $roomtypeInfo->r_price) ? $roomtypeInfo->s_price : $roomtypeInfo->r_price;
            $roomMember = 0;
            foreach ($roomInfo['room'] as $key => $room) {
                $roomMember += count($room['members']);
                $membersArr = array_merge($membersArr, $room['members']);
                $noOfTotlaRooms++;
            }
            $roomTypes[$i]['noOfmembers'] = $roomMember;
            
            $roomTypePrice += round($roomTypes[$i]['price']*$roomMember);
            $totalDayPrice += round($totalDay*$roomTypePrice);

            $i++;
        }

        $bookingSummary['noOfMembers'] = count($membersArr);
        $bookingSummary['noOfTotlaRooms'] = $noOfTotlaRooms;
        $bookingSummary['totalDay'] = $totalDay;
        $bookingSummary['totalDayPrice'] = $totalDayPrice;
        $bookingSummary['roomTypePrice'] = $roomTypePrice;
        $bookingSummary['roomTypes'] = $roomTypes;
        $bookingSummary['roomTypes'] = $roomTypes;

        // dd($bookingSummary);
        return response()->json(['status' => true, 'message' => 'Booking Summary Details', 'bookingSummary' => $bookingSummary]);

    }

}