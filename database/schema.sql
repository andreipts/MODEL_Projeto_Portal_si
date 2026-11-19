-- SCRIPT DDL: portal_si
-- Tabelas, índices, funções e triggers do Portal de Comunicação SI

CREATE TABLE IF NOT EXISTS usuarios (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    senha_hash VARCHAR(255) NOT NULL,
    perfil VARCHAR(20) DEFAULT 'admin',
    ativo BOOLEAN DEFAULT true,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS categorias (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    slug VARCHAR(120) UNIQUE NOT NULL,
    descricao TEXT,
    icone VARCHAR(50),
    ordem INTEGER DEFAULT 0,
    ativo BOOLEAN DEFAULT true
);

CREATE TABLE IF NOT EXISTS conteudos (
    id SERIAL PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    slug VARCHAR(220) UNIQUE NOT NULL,
    resumo TEXT,
    corpo TEXT,
    imagem_capa VARCHAR(255),
    link_youtube VARCHAR(255),
    categoria_id INTEGER REFERENCES categorias(id) ON DELETE RESTRICT,
    autor_id INTEGER REFERENCES usuarios(id) ON DELETE RESTRICT,
    status VARCHAR(20) DEFAULT 'publicado',
    destaque BOOLEAN DEFAULT false,
    publicado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS tags (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(80) NOT NULL,
    slug VARCHAR(100) UNIQUE NOT NULL
);

CREATE TABLE IF NOT EXISTS conteudo_tags (
    conteudo_id INTEGER REFERENCES conteudos(id) ON DELETE CASCADE,
    tag_id INTEGER REFERENCES tags(id) ON DELETE RESTRICT,
    PRIMARY KEY (conteudo_id, tag_id)
);

CREATE TABLE IF NOT EXISTS eventos (
    id SERIAL PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    descricao TEXT,
    data_inicio TIMESTAMP NOT NULL,
    data_fim TIMESTAMP,
    local VARCHAR(150),
    link_inscricao VARCHAR(255),
    autor_id INTEGER REFERENCES usuarios(id) ON DELETE RESTRICT,
    status VARCHAR(20) DEFAULT 'publicado',
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS midias (
    id SERIAL PRIMARY KEY,
    nome_arquivo VARCHAR(255) NOT NULL,
    caminho VARCHAR(255) NOT NULL,
    tipo_mime VARCHAR(100),
    tamanho_bytes INTEGER,
    enviado_por INTEGER REFERENCES usuarios(id) ON DELETE SET NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS auditoria (
    id SERIAL PRIMARY KEY,
    usuario_id INTEGER REFERENCES usuarios(id) ON DELETE SET NULL,
    acao VARCHAR(50) NOT NULL,
    tabela_afetada VARCHAR(50),
    registro_id INTEGER,
    detalhes TEXT,
    ip_origem VARCHAR(45),
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX IF NOT EXISTS idx_conteudos_categoria ON conteudos (categoria_id);
CREATE INDEX IF NOT EXISTS idx_conteudos_autor ON conteudos (autor_id);
CREATE INDEX IF NOT EXISTS idx_conteudos_slug ON conteudos (slug);
CREATE INDEX IF NOT EXISTS idx_conteudos_status ON conteudos (status);
CREATE INDEX IF NOT EXISTS idx_conteudos_publicado_em ON conteudos (publicado_em);
CREATE INDEX IF NOT EXISTS idx_eventos_autor ON eventos (autor_id);
CREATE INDEX IF NOT EXISTS idx_eventos_status ON eventos (status);
CREATE INDEX IF NOT EXISTS idx_categorias_slug ON categorias (slug);

CREATE OR REPLACE FUNCTION set_atualizado_em()
RETURNS TRIGGER AS $$
BEGIN
    NEW.atualizado_em = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS trg_usuarios_atualizado_em ON usuarios;
CREATE TRIGGER trg_usuarios_atualizado_em
BEFORE UPDATE ON usuarios
FOR EACH ROW EXECUTE PROCEDURE set_atualizado_em();

DROP TRIGGER IF EXISTS trg_conteudos_atualizado_em ON conteudos;
CREATE TRIGGER trg_conteudos_atualizado_em
BEFORE UPDATE ON conteudos
FOR EACH ROW EXECUTE PROCEDURE set_atualizado_em();

DROP TRIGGER IF EXISTS trg_eventos_atualizado_em ON eventos;
CREATE TRIGGER trg_eventos_atualizado_em
BEFORE UPDATE ON eventos
FOR EACH ROW EXECUTE PROCEDURE set_atualizado_em();
