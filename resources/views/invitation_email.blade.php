Hello {{ $details["invitee_name"] }},<br />

<p>You have been invited to the following event:</p>

<p>
What:	{{ $details["event_description"] }}<br />

When:	{{ $details["event_date"] }}, {{$details["event_time"]}}<br />

Where:	{{ $details["event_location"] }}<br />

Who:	- {{ $details["organizer_name"] }}<br />
		- {{ $details["invitee_name"] }}
</p>

<p>Please confirm if you be able to join the meeting.</p>
<br />
Best regards,<br />
{{ $details["organizer_name"] }}