-- Table: opcastroficas.beneficiario

-- DROP TABLE opcastroficas.beneficiario;

CREATE TABLE opcastroficas.beneficiario
(
  numero_beneficiario character(100),
  tipo_documento character(5) NOT NULL,
  nro_documento character(20) NOT NULL,
  apellido character(100),
  nombre character(100),
  edad integer,
  CONSTRAINT beneficiario_index01 PRIMARY KEY (tipo_documento, nro_documento)
)
WITH (
  OIDS=FALSE
);
ALTER TABLE opcastroficas.beneficiario
  OWNER TO postgres;
