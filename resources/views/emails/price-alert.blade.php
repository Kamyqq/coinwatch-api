<h1>Hello {{ $alert->user->name }}!</h1>
<p>Your alert for <strong>{{ $alert->cryptocurrency->name }} ({{ $alert->cryptocurrency->symbol }})</strong> has been triggered!</p>
<p>The target price was: {{ $alert->target_price }} PLN.</p>
<p>Current price is: {{ $alert->cryptocurrency->price }} PLN.</p>
<br>
<p>CoinWatch Team</p>
