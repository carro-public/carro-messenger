# Message was sent event

We extends Laravel's Notification to send messages.

So, response from services won't be returned to caller.

To solve this, we added an event called `MessageWasSent` event..

It will be fired every time when you send messages if you set env `EVENT_IS_CALLED` as true

You can bind with Listener as you wish