<form method="post" action="{{ route('payment') }}">
    {{ csrf_field() }}

    <div class="form-group">
        <label for="card_number">Card Number:</label>
        <input type="text" class="form-control" id="card_number" name="card_number" required>
    </div>

    <div class="form-group">
        <label for="expiration_month">Expiration Month:</label>
        <select class="form-control" id="expiration_month" name="expiration_month" required>
            @for ($month = 1; $month <= 12; $month++)
                <option value="{{ $month }}">{{ $month }}</option>
            @endfor
        </select>
    </div>

    <div class="form-group">
        <label for="expiration_year">Expiration Year:</label>
        <select class="form-control" id="expiration_year" name="expiration_year" required>
            @for ($year = 2023; $year <= 2030; $year++)
                <option value="{{ $year }}">{{ $year }}</option>
            @endfor
        </select>
    </div>

    <div class="form-group">
        <label for="cvc">CVC:</label>
        <input type="text" class="form-control" id="cvc" name="cvc" required>
    </div>

    <button type="submit" class="btn btn-primary">Pay</button>
</form>
