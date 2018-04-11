use basecafetera;

drop table if exists paises;

create table paises(
	id_pais int unsigned auto_increment,
    nombre varchar(120) not null,
    constraint pk_pais primary key (id_pais)
);

drop table if exists oyentes;

create table oyentes(
	id_oyente int unsigned auto_increment,
    twitter varchar(50) not null,
    id_pais int unsigned not null,
    lat double,
    lon double,
    -- No visibles
    nombre varchar(120) not null,
    apellido varchar(120) not null,
    email varchar(120) not null,
    mecenas bit not null default 0,
    fecha_nacimiento date not null,
    telefono varchar(30) not null,
    clave char(40) not null,
    activo timestamp,
    -- administracion
    admin boolean default false,
    constraint pk_oyen primary key (id_oyente),
    constraint uk_oyen_emai unique (email),
    constraint fk_oyen_pais foreign key (id_pais) references paises(id_pais)
);

drop table if exists intereses;

create table intereses(
	id_interes int unsigned auto_increment,
    nombre varchar(120) not null,
    foto varchar(30) not null,
    constraint pk_inte primary key (id_interes)
);

drop table if exists inte_oyen;

create table inte_oyen(
    id_interes int unsigned,
    id_oyente int unsigned,
    constraint pk_inte_oyen primary key (id_oyente, id_interes),
    constraint fk_inte_oyen_inte foreign key (id_interes) references intereses(id_interes),
    constraint fk_inte_oyen_oyen foreign key (id_oyente) references oyentes(id_oyente)
);

