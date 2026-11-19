<?php
function e(?string $valor): string
{
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

function formatarData(?string $data, string $formato = 'd/m/Y'): string
{
    if (empty($data)) {
        return '';
    }
    $timestamp = strtotime($data);
    return $timestamp ? date($formato, $timestamp) : '';
}

function resumirTexto(?string $texto, int $limite = 160): string
{
    $limpo = trim(strip_tags((string) $texto));
    if (mb_strlen($limpo) <= $limite) {
        return $limpo;
    }
    return mb_substr($limpo, 0, $limite) . '...';
}

function extrairIdYoutube(?string $url): ?string
{
    if (empty($url)) {
        return null;
    }

    $padroes = [
        '/youtube\.com\/watch\?v=([A-Za-z0-9_-]{11})/',
        '/youtu\.be\/([A-Za-z0-9_-]{11})/',
        '/youtube\.com\/embed\/([A-Za-z0-9_-]{11})/',
    ];

    foreach ($padroes as $padrao) {
        if (preg_match($padrao, $url, $m)) {
            return $m[1];
        }
    }

    return null;
}

function caminhoUploadAbsoluto(string $relativo): string
{
    return BASE_PATH . '/public/' . ltrim($relativo, '/');
}

function processarUploadImagem(array $arquivo, array &$erros, ?string $imagemAtual = null): ?string
{
    if (!isset($arquivo['error']) || $arquivo['error'] === UPLOAD_ERR_NO_FILE) {
        return $imagemAtual;
    }

    if ($arquivo['error'] !== UPLOAD_ERR_OK) {
        $erros[] = 'Erro operacional no envio da imagem. Código: ' . (int) $arquivo['error'];
        return $imagemAtual;
    }

    $tamanhoMaximo = 2 * 1024 * 1024;
    if (($arquivo['size'] ?? 0) > $tamanhoMaximo) {
        $erros[] = 'A imagem selecionada excede o limite estrito de 2 MB.';
        return $imagemAtual;
    }

    $tmp = $arquivo['tmp_name'] ?? '';
    if ($tmp === '' || !is_uploaded_file($tmp)) {
        $erros[] = 'Arquivo de imagem inválido.';
        return $imagemAtual;
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeDetectado = $finfo->file($tmp);
    $mimesPermitidos = [
        'image/jpeg' => 'jpg',
        'image/pjpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
    ];

    if (!isset($mimesPermitidos[$mimeDetectado])) {
        $erros[] = 'Formato de imagem inválido. São aceitos apenas arquivos JPG, PNG ou GIF.';
        return $imagemAtual;
    }

    $extensao = $mimesPermitidos[$mimeDetectado];
    $nomeArquivo = 'conteudo_' . uniqid('', true) . '.' . $extensao;
    $diretorio = BASE_PATH . '/public/assets/uploads/';

    if (!is_dir($diretorio) && !mkdir($diretorio, 0755, true) && !is_dir($diretorio)) {
        $erros[] = 'Falha ao criar o diretório de uploads.';
        return $imagemAtual;
    }

    $destino = $diretorio . $nomeArquivo;
    if (!move_uploaded_file($tmp, $destino)) {
        $erros[] = 'Falha ao gravar arquivo de imagem no disco.';
        return $imagemAtual;
    }

    if (!empty($imagemAtual)) {
        $antigo = caminhoUploadAbsoluto($imagemAtual);
        if (is_file($antigo)) {
            unlink($antigo);
        }
    }

    return 'assets/uploads/' . $nomeArquivo;
}

function removerArquivoImagem(?string $relativo): void
{
    if (empty($relativo)) {
        return;
    }
    $arquivo = caminhoUploadAbsoluto($relativo);
    if (is_file($arquivo)) {
        unlink($arquivo);
    }
}

function calcularPaginacao(int $total, int $pagina, int $porPagina): array
{
    $porPagina = max(1, $porPagina);
    $totalPaginas = (int) ceil($total / $porPagina);
    $pagina = max(1, min($pagina, max(1, $totalPaginas)));
    $offset = ($pagina - 1) * $porPagina;

    return [
        'offset' => $offset,
        'totalPaginas' => $totalPaginas,
        'paginaAtual' => $pagina,
    ];
}
