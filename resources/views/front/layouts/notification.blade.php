<div class="notification dropdown">
    <a class="notificationToggle" href="#" data-label="{{ $notifications->count() }}" role="button"
        data-bs-toggle="dropdown" aria-expanded="false">
        <img src="{{ asset('front/img/notification-icon.svg') }}" alt="Notification" width="18" height="18">
    </a>
    <div class="dropdown-menu">
        <div class="notificationList">
            <strong>Notifications</strong>
            @if ($notifications->isEmpty())
                <p>No notifications found.</p>
            @else
                <ul class="">
                    @foreach ($notifications as $notification)
                        <li>
                            <div class="notifiContent">
                                <h4>{{ $notification->data['greeting'] }}
                                    <b>{{ $notification->created_at->format('d/m') }}</b>
                                </h4>
                                <p>{{ $notification->data['body'] }}
                                </p>
                                <a href="{{ $notification->data['actionURL'] }}" class="btn btn-secondary notification-link"
                                    data-notification-id="{{ $notification->id }}">
                                    Begin Training
                                </a>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif

        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script>
    // Assuming you are using jQuery for simplicity
    $(document).ready(function() {
        $('.notification-link').on('click', function(e) {
            e.preventDefault();

            var notificationId = $(this).data('notification-id');

            // Send an AJAX request to mark the notification as read
            $.ajax({
                url: "{{ route('markAsRead') }}",
                method: 'POST',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'notification_id': notificationId,
                },
                success: function(response) {
                    // You can handle the response if needed
                    console.log(response);
                },
                error: function(error) {
                    // Handle errors if any
                    console.error(error);
                }
            });
            window.location.href = $(this).attr('href');
        });
    });
</script>
<!--Start of Tawk.to Script-->
<script type="text/javascript">
    var Tawk_API = Tawk_API || {},
        Tawk_LoadStart = new Date();
    (function() {
        var s1 = document.createElement("script"),
            s0 = document.getElementsByTagName("script")[0];
        s1.async = true;
        s1.src = 'https://embed.tawk.to/6567062926949f791135d40c/1hgd705id';
        s1.charset = 'UTF-8';
        s1.setAttribute('crossorigin', '*');
        s0.parentNode.insertBefore(s1, s0);
    })();
</script>
<!--End of Tawk.to Script-->
