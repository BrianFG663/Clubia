<form method="POST" action="{{ route('partner.login') }}">
    @csrf
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="DNI" required>
    <button type="submit">Ingresar</button>
</form>
