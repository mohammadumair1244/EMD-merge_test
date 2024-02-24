@extends('main') 
@section('content')
    <section  class="banner">
        <div class="container terms-and-conditions">
            {!! get_setting_by_key('terms-and-condition')->value !!}  
        </div>
    </section>
  
@endsection

