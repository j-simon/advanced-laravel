
<p>
    Bienen sind toll. Sie produzieren leckeren Honig!
</p>

<h4>Verlinkung aus der Mail auf den Webspace, viele Mail-Programme zeigen solche Bilder nicht an, erst nachdem man 'Bilder laden' aktiviert werden Bilder angezeigt</h4>
<img width="100px" src="{{asset('img/bee.jpg')}}" />

<h4>Das Bild einbetten</h4>
<img width="100px" src="{{ $message->embed(base_path().'/public/img/bee.jpg') }}">
{{-- <img width="100px" src="{{$message->embed(asset('storage/img/bee.jpg'))}}" /> --}}

 

 


 
