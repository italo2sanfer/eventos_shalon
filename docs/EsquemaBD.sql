CREATE table evento(
  id int auto_increment,
  nome varchar(100) not null,
  descricao varchar(255) default " ",
  local varchar(100) default " ",
  endereco varchar(200) default " ",
  aberto bool default true,
  primary key(id)
);
CREATE table tipo_instituicao(
  id int auto_increment,
  descricao varchar(255) not null,
  primary key(id)
);
CREATE table instituicao(
  id int auto_increment,
  tipo_instituicao_id int,
  nome varchar(100) not null,
  nome_responsavel varchar(100) not null,
  endereco varchar(200) default " ",
  primary key (id),
  foreign key (tipo_instituicao_id)
    references tipo_instituicao(id)
);
CREATE table pessoa(
  id int auto_increment,
  cpf varchar(20) not null,
  nome varchar(100) not null,
  email varchar(50) not null,
  telefone_contato varchar(20) not null,
  primary key (id)
);
CREATE table papel(
  id int auto_increment,
  descricao varchar(255) not null,
  primary key (id)
);
CREATE table participante(
  id int auto_increment,
  evento_id int not null,
  pessoa_id int not null,
  papel_id int not null,
  instituicao_id int not null,
  situacao varchar(50) not null,
  primary key(id),
  foreign key (evento_id)
    references evento(id),
  foreign key (pessoa_id)
    references pessoa(id),
  foreign key (papel_id)
    references papel(id),
  foreign key (instituicao_id)
    references instituicao(id)
);
CREATE table perfil_usuario(
  id int auto_increment,
  nome varchar(20) not null,
  descricao varchar(255) default " ",
  primary key(id)
);
CREATE table usuario(
  id int auto_increment,
  nome_usuario varchar(20) not null,
  senha varchar(255) not null,
  pessoa_id int not null,
  perfil_usuario_id int not null,
  primary key(id),
  foreign key (pessoa_id)
    references pessoa(id),
  foreign key (perfil_usuario_id)
    references perfil_usuario(id)
);

ALTER TABLE participante ADD CONSTRAINT constraint_participante UNIQUE (pessoa_id, evento_id, papel_id, instituicao_id);
ALTER TABLE pessoa ADD CONSTRAINT constraint_pessoa UNIQUE (cpf, email);
ALTER TABLE usuario ADD CONSTRAINT constraint_usuario UNIQUE (pessoa_id, perfil_usuario_id);

INSERT INTO evento (nome) VALUES("Assembleia Associacao Batista");
INSERT INTO tipo_instituicao (descricao) VALUES ("igreja");
INSERT INTO papel (descricao) VALUES ("organizador");
INSERT INTO papel (descricao) VALUES ("convidado");
INSERT INTO perfil_usuario (nome,descricao) VALUES ("admin","Pode executar qualquer acao no sistema.");
INSERT INTO perfil_usuario (nome,descricao) VALUES ("comum","Pode executar somente o basico no sistema.");
