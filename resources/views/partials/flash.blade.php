<style>

    .custom-alert{

        width:100%;

        display:flex;

        align-items:flex-start;
        justify-content:space-between;

        gap:16px;

        padding:18px 20px;

        margin-bottom:22px;

        border-radius:22px;

        backdrop-filter:blur(14px);

        border:1px solid rgba(255,255,255,0.08);

        animation:fadeIn 0.25s ease;
    }

    .alert-content{

        display:flex;

        align-items:flex-start;

        gap:14px;

        flex:1;
    }

    .alert-content i{

        font-size:18px;

        margin-top:2px;
    }

    .success-alert{

        background:
            rgba(34,197,94,0.12);

        border-color:
            rgba(34,197,94,0.22);

        color:#dcfce7;
    }

    .error-alert{

        background:
            rgba(239,68,68,0.12);

        border-color:
            rgba(239,68,68,0.20);

        color:#fecaca;
    }

    .alert-error-item{

        font-size:14px;

        line-height:1.7;
    }

    .alert-close{

        border:none;

        background:transparent;

        color:inherit;

        opacity:0.7;

        cursor:pointer;

        transition:0.25s;

        font-size:18px;
    }

    .alert-close:hover{

        opacity:1;

        transform:scale(1.08);
    }

    @keyframes fadeIn{

        from{

            opacity:0;
            transform:translateY(-8px);
        }

        to{

            opacity:1;
            transform:translateY(0);
        }
    }

</style>

@if (session('status') || session('success'))

    <div class="custom-alert success-alert alert alert-dismissible fade show">

        <div class="alert-content">

            <i class="fa-solid fa-circle-check"></i>

            <span>
                {{ session('status') ?? session('success') }}
            </span>

        </div>

        <button
            type="button"
            class="alert-close"
            data-bs-dismiss="alert"
            aria-label="Close"
        >
            <i class="fa-solid fa-xmark"></i>
        </button>

    </div>

@endif

@if ($errors->any())

    <div class="custom-alert error-alert alert alert-dismissible fade show">

        <div class="alert-content">

            <i class="fa-solid fa-triangle-exclamation"></i>

            <div>

                @foreach ($errors->all() as $error)

                    <div class="alert-error-item">
                        {{ $error }}
                    </div>

                @endforeach

            </div>

        </div>

        <button
            type="button"
            class="alert-close"
            data-bs-dismiss="alert"
            aria-label="Close"
        >
            <i class="fa-solid fa-xmark"></i>
        </button>

    </div>

@endif