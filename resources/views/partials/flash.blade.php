@if (session('status') || session('success') || $errors->any())

    <div class="flash-wrapper">

        {{-- SUCCESS --}}
        @if (session('status') || session('success'))

            <div class="flash-alert success-alert">

                <div class="flash-content">

                    <i class="fa-solid fa-circle-check"></i>

                    <span>
                        {{ session('status') ?? session('success') }}
                    </span>

                </div>

                <button
                    type="button"
                    class="flash-close"
                    onclick="this.parentElement.remove()"
                >
                    <i class="fa-solid fa-xmark"></i>
                </button>

            </div>

        @endif

        {{-- ERROR --}}
        @if ($errors->any())

            @foreach ($errors->all() as $error)

                <div class="flash-alert error-alert">

                    <div class="flash-content">

                        <i class="fa-solid fa-circle-exclamation"></i>

                        <span>
                            {{ $error }}
                        </span>

                    </div>

                    <button
                        type="button"
                        class="flash-close"
                        onclick="this.parentElement.remove()"
                    >
                        <i class="fa-solid fa-xmark"></i>
                    </button>

                </div>

            @endforeach

        @endif

    </div>

@endif

<style>

    .flash-wrapper{

        width:100%;

        display:flex;

        flex-direction:column;

        align-items:center;

        gap:14px;

        margin-bottom: -10px;
		
		margin-top: -10px;
    }

    .flash-alert{

        width:fit-content;

        max-width:520px;

        padding:14px 18px;

        border-radius:18px;

        display:flex;

        align-items:center;

        justify-content:space-between;

        gap:20px;

        backdrop-filter:blur(18px);

        animation:fadeDown 0.35s ease;

        box-shadow:
            0 10px 30px rgba(0,0,0,0.22);
    }

    .flash-content{

        display:flex;

        align-items:center;

        gap:12px;

        font-size:14px;

        font-weight:500;

        line-height:1.5;
    }

    .flash-content i{

        font-size:16px;

        flex-shrink:0;
    }

    .flash-close{

        border:none;

        background:transparent;

        color:inherit;

        cursor:pointer;

        padding:0;

        font-size:15px;

        opacity:0.7;

        transition:0.25s;
    }

    .flash-close:hover{

        opacity:1;

        transform:scale(1.08);
    }

    .success-alert{

        background:
            rgba(34,197,94,0.15);

        border:
            1px solid rgba(34,197,94,0.25);

        color:#bbf7d0;
    }

    .error-alert{

        background:
            rgba(239,68,68,0.15);

        border:
            1px solid rgba(239,68,68,0.25);

        color:#fecaca;
    }

    @keyframes fadeDown{

        from{

            opacity:0;

            transform:
                translateY(-12px);
        }

        to{

            opacity:1;

            transform:
                translateY(0);
        }
    }

</style>