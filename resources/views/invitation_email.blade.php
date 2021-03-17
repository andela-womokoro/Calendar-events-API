Hello {{ $details["invitee_name"] }},<br />

<p>You have been invited to the following event:</p>

<p>
What:	{{ $details["event_description"] }}<br /><br />

When:	{{ $details["event_date"] }}, {{$details["event_time"]}}<br /><br />

Where:	{{ $details["event_location"] }}<br />
<pre>
Who:	- {{ $details["organizer_name"] }}<br />
	- {{ $details["invitee_name"] }}
</pre>
</p>

<p>Please confirm if you be able to join the meeting.</p>
<br />
Best regards,<br />
{{ $details["organizer_name"] }}