<div class="notification {{$class}}">
    <div class="notification-content position-relative">
        @if(@$title)
            <p class="m-0">{{ @$title }}</p>
        @endif
        <p class="m-0">{!! $message !!}</p>
        <div class="position-absolute top-0 end-0 d-flex justify-content-center align-items-center" style="width: 2em;height: 2em;text-align: center;">
            <a class="close btn-close" style="height: 5px;width: 5px;"></a>
        </div>
    </div>
</div>

<script>
    document.getElementsByClassName("close")[0].addEventListener("click", () => {
        document.getElementsByClassName("notification")[0].remove()
    });

    if({{ @$isAutoClose }}){
        setTimeout(() => {
            document.getElementsByClassName("notification")[0].remove()
        }, 30000);
    }
</script>
