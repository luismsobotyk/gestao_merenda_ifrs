<h1>Bem-vindo ao SISGEM (Área Restrita)</h1>
<hr>
<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit" class="btn btn-danger">
        Sair
    </button>
</form>
