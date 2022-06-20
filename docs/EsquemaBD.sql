create table evento(
  id int auto_increment,
  nome varchar(100) not null,
  descricao varchar(255) default " ",
  local varchar(100) default " ",
  endereco varchar(200) default " ",
  aberto bool default true,
  primary key(id)
);
create table tipo_instituicao(
  id int auto_increment,
  descricao varchar(255) not null,
  primary key(id)
);
create table instituicao(
  id int auto_increment,
  tipo_instituicao_id int,
  nome varchar(100) not null,
  nome_responsavel varchar(100) not null,
  endereco varchar(200) default " ",
  primary key (id),
  foreign key (tipo_instituicao_id)
    references tipo_instituicao(id)
);
create table pessoa(
  id int auto_increment,
  cpf varchar(20) not null,
  nome varchar(100) not null,
  email varchar(50) not null,
  telefone_contato varchar(20) not null,
  primary key (id)
);
create table papel(
  id int auto_increment,
  descricao varchar(255) not null,
  primary key (id)
);
create table participante(
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
create table perfil_usuario(
  id int auto_increment,
  nome varchar(20) not null,
  descricao varchar(255) default " ",
  primary key(id)
);
create table usuario(
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
insert into evento (nome) values("Assembleia Associacao Batista");
insert into tipo_instituicao (descricao) values ("igreja");
insert into papel (descricao) values ("organizador");
insert into papel (descricao) values ("convidado");
insert into perfil_usuario (nome,descricao) values ("admin","Pode executar qualquer acao no sistema.");
insert into perfil_usuario (nome,descricao) values ("comum","Pode executar somente o basico no sistema.");
