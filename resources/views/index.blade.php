<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>AI Solutions</title>
    <link href="{{ asset('css/reset.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/style.css') }}" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/c1b057903f.js" crossorigin="anonymous"></script>
</head>
<body>
<main>
    <form action="{{ route('add') }}" method="post" enctype="multipart/form-data">
        <h2>Selecione um JSON para adicionar na fila de processamento</h2>
        <input type="file" name="file" />
        @csrf
        <button type="submit">Importar JSON</button>
    </form>
    <div class="run">
        <a href="/run">Iniciar processamento</a>
    </div>
    <h2>Jobs</h2>
    <table class="jobs">
        <thead>
        <tr>
            <td>Status</td>
            <td>Arquivo</td>
            <td>Data de Criação</td>
            <td>Data de Alteração</td>
        </tr>
        </thead>
        @foreach($jobs as $j)
            <tr class="{{ $j['status'] === 1 ? 'done' : '' }}">
                <td>
                    {{ $j['status'] === 0 ? 'Para enviar' : 'Enviado' }}
                </td>
                <td>
                    {{ $j['file_path'] }}
                </td>
                <td>
                    {{ \Carbon\Carbon::parse($j['created_at'])->format('d/m/Y H:i') }}
                </td>
                <td>
                    {{ \Carbon\Carbon::parse($j['updated_at'])->format('d/m/Y H:i') }}
                </td>
            </tr>
        @endforeach
    </table>
    <h2>Documentos</h2>
    <table class="documents">
        <thead>
        <tr>
            <td>Categoria</td>
            <td>Titulo</td>
            <td>Data de Criação</td>
            <td>Conteúdo</td>
        </tr>
        </thead>
        @foreach($documents as $d)
            <tr>
                <td>
                    {{ $d['category']['name'] }}
                </td>
                <td>
                    {{ $d['title'] }}
                </td>
                <td>
                    {{ \Carbon\Carbon::parse($d['created_at'])->format('d/m/Y H:i') }}
                </td>
                <td>
                    {{ \Illuminate\Support\Str::limit($d['contents'], 50) }}
                </td>
            </tr>
        @endforeach
    </table>
</main>
<div class="message @if (session('message')) active @endif">
    <i class="fas fa-times-circle"></i>
    @if (session('message')){{ session('message') }}@endif
</div>
<script>
    const messageElement = document.querySelector('.message')
    messageElement.addEventListener('click', () => {
        messageElement.classList.remove('active')
    })

    setTimeout(() => {
        messageElement.classList.remove('active')
    }, 5000)
</script>
</body>
</html>
