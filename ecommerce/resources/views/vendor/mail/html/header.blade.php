<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="{{asset('img/logo-black.svg')}}" width="100px" height="auto" class="logo" alt="Koras Logo">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
