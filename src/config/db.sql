-- Tabla única para Cursos/Documentos y Library
CREATE TABLE IF NOT EXISTS reference_docs (
    id SERIAL PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    content_html TEXT, -- Aquí se guarda el código HTML del documento
    category VARCHAR(100), -- 'dashboard' o 'library'
    thumbnail TEXT,
    external_url TEXT, -- Opcional, por si es un video
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);