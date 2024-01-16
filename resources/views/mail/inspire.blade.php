@component('mail::message')
# Quote

Hi, ist die Mail angekommen?

@component('mail::quote',['author' => $author])
{{$quote}} # Slot Variable!
@endcomponent

Viele Grüße<br>

@endcomponent