-- Dados iniciais para homologação do Portal SI
-- Execute depois de schema.sql no banco portal_si

INSERT INTO categorias (nome, slug, descricao, ordem, ativo) VALUES
('Home', 'home', 'Conteúdo da página inicial', 1, true),
('Sobre o Curso', 'sobre-o-curso', 'Informações sobre o curso', 2, true),
('Ações Sociais', 'acoes-sociais', 'Ações sociais do curso', 3, true),
('Atividades Complementares', 'atividades-complementares', 'Atividades complementares', 4, true),
('Projetos de Extensão', 'projetos-de-extensao', 'Projetos de extensão', 5, true),
('Eventos', 'eventos', 'Eventos do curso', 6, true),
('Notícias da ESUCRI', 'noticias-da-esucri', 'Notícias institucionais da ESUCRI', 7, true),
('Eventos Acadêmicos', 'eventos-academicos', 'Eventos acadêmicos do curso', 8, true),
('Estágios e Oportunidades', 'estagios-e-oportunidades', 'Vagas de estágio e oportunidades', 9, true),
('Artigos e Tutoriais', 'artigos-e-tutoriais', 'Artigos e tutoriais técnicos', 10, true)
ON CONFLICT (slug) DO NOTHING;

INSERT INTO tags (nome, slug) VALUES
('Institucional', 'institucional'),
('Extensão', 'extensao'),
('Acadêmico', 'academico')
ON CONFLICT (slug) DO NOTHING;

-- O usuário administrador é criado no primeiro acesso a /login
-- (admin@esucri.com.br / 123456) se a tabela usuarios estiver vazia.
