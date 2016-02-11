Create table Usuario(
usuario varchar(15) primary key,
password integer
);


Create table Estudiante(
id SERIAL,
codigo integer,
nombre varchar(30),
apellido varchar(50),
cedula integer,
edad integer,
semestre integer,
CONSTRAINT estudiante_pkey PRIMARY KEY (id)
);