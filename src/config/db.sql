CREATE TABLE tutorials (
    id SERIAL PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    type VARCHAR(50),
    location VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Para añadir la columna si no existe
ALTER TABLE reference_docs ADD COLUMN IF NOT EXISTS pdf_url VARCHAR(255);

-- Ejemplo para vincular un archivo a una noticia específica (ID 1)
UPDATE reference_docs 
SET pdf_url = 'uploads/pdf/psicologia-color.pdf' 
WHERE id = 1;