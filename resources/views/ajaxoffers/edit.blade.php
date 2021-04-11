@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="alert alert-success" id="success_msg" style="display: none;">
            تم التحديث بنجاح
        </div>
        <div class="flex-center position-ref full-height">
            <div class="content">
                <div class="title m-b-md">
                    {{__('messages.Add your offer')}}
                </div>
                @if(Session::has('success'))
                    <div class="alert alert-success" role="alert">
                        {{ Session::get('success') }}
                    </div>
                @endif

                <br>
                <form method="POST" id="offerFormUpdate" action="{{route('offers.store')}}" enctype="multipart/form-data">
                    @csrf
                    {{--<input name="_toker" value="{{csrf_token()}}">--}}
                    <div class="form-group">
                        <label for="exampleInputEmail1">اختر صوره العرض</label>
                        <input type="file" class="form-control" name="photo">
                        @error('photo')
                        <small class="form-text text-danger">{{$message}}</small>
                        @enderror
                    </div>
                    <input type="text" style="display: none;" class="form-control" value="{{$offer -> id}}" name="offer_id">
                    <div class="form-group">
                        <label for="exampleInputEmail1">{{__('messages.Offer Name ar')}}</label>
                        <input type="text" class="form-control" value="{{$offer->name_ar}}" name="name_ar" placeholder="{{__('messages.Offer Name ar')}}">
                        @error('name_ar')
                        <small class="form-text text-danger">{{$message}}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">{{__('messages.Offer Name en')}}</label>
                        <input type="text" class="form-control" value="{{$offer->name_en}}" name="name_en" placeholder="{{__('messages.Offer Name en')}}">
                        @error('name_en')
                        <small class="form-text text-danger">{{$message}}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">{{__('messages.Offer Price')}}</label>
                        <input type="number" class="form-control" value="{{$offer->price}}" name="price" placeholder="{{__('messages.Offer Price')}}">
                        @error('price')
                        <small class="form-text text-danger">{{$message}}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">{{__('messages.Offer details ar')}}</label>
                        <input type="text" class="form-control" value="{{$offer->details_ar}}" name="details_ar" placeholder="{{__('messages.Offer details ar')}}">
                        @error('details_ar')
                        <small class="form-text text-danger">{{$message}}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="exampleInputPassword1">{{__('messages.Offer details en')}}</label>
                        <input type="text" class="form-control" value="{{$offer->details_en}}" name="details_en" placeholder="{{__('messages.Offer details en')}}">
                        @error('details_en')
                        <small class="form-text text-danger">{{$message}}</small>
                        @enderror
                    </div>
                    <button id="update_offer" class="btn btn-primary">{{__('messages.Save Offer')}}</button>
                </form>

            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script>

        $(document).on('click','#update_offer', function (e){
            e.preventDefault();

            var formData = new FormData($('#offerFormUpdate')[0]);

            $.ajax({
                type: 'post',
                enctype: 'multipart/dorm-data',
                url: "{{Route('ajax.offers.update')}}",
                data: formData,
                processData:false,
                contentType:false,
                cache: false,
                success: function (data) {
                    if(data.status == true){
                        $('#success_msg').show();
                    }
                }, error: function (reject){

                }
            });
        });

    </script>
@stop
