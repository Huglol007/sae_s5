--
-- PostgreSQL database dump
--

-- Dumped from database version 16.8 (Debian 16.8-1.pgdg120+1)
-- Dumped by pg_dump version 16.8 (Homebrew)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: public; Type: SCHEMA; Schema: -; Owner: -
--

-- *not* creating schema, since initdb creates it


--
-- Name: notify_messenger_messages(); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION public.notify_messenger_messages() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
            BEGIN
                PERFORM pg_notify('messenger_messages', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$;


SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: creneau; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.creneau (
    id integer NOT NULL,
    matiere_id integer,
    enseignant_id integer,
    ressource_id integer,
    start_date timestamp(0) without time zone NOT NULL,
    duree integer,
    promotion_id integer,
    type character varying(50) DEFAULT 'cours'::character varying NOT NULL,
    end_date timestamp(0) without time zone DEFAULT NULL::timestamp without time zone
);


--
-- Name: creneau_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.creneau_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: creneau_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.creneau_id_seq OWNED BY public.creneau.id;


--
-- Name: doctrine_migration_versions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.doctrine_migration_versions (
    version character varying(191) NOT NULL,
    executed_at timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    execution_time integer
);


--
-- Name: enseignant; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.enseignant (
    id integer NOT NULL,
    utilisateur_id integer NOT NULL,
    type_enseignant_id integer NOT NULL
);


--
-- Name: enseignant_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.enseignant_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: enseignant_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.enseignant_id_seq OWNED BY public.enseignant.id;


--
-- Name: matiere; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.matiere (
    id integer NOT NULL,
    enseignant_id integer,
    name character varying(255) NOT NULL,
    volume_horaire integer NOT NULL
);


--
-- Name: matiere_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.matiere_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: matiere_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.matiere_id_seq OWNED BY public.matiere.id;


--
-- Name: messenger_messages; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.messenger_messages (
    id bigint NOT NULL,
    body text NOT NULL,
    headers text NOT NULL,
    queue_name character varying(190) NOT NULL,
    created_at timestamp(0) without time zone NOT NULL,
    available_at timestamp(0) without time zone NOT NULL,
    delivered_at timestamp(0) without time zone DEFAULT NULL::timestamp without time zone
);


--
-- Name: COLUMN messenger_messages.created_at; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.messenger_messages.created_at IS '(DC2Type:datetime_immutable)';


--
-- Name: COLUMN messenger_messages.available_at; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.messenger_messages.available_at IS '(DC2Type:datetime_immutable)';


--
-- Name: COLUMN messenger_messages.delivered_at; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.messenger_messages.delivered_at IS '(DC2Type:datetime_immutable)';


--
-- Name: messenger_messages_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.messenger_messages_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: messenger_messages_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.messenger_messages_id_seq OWNED BY public.messenger_messages.id;


--
-- Name: promotion; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.promotion (
    id integer NOT NULL,
    year_level character varying(255) NOT NULL
);


--
-- Name: promotion_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.promotion_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: promotion_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.promotion_id_seq OWNED BY public.promotion.id;


--
-- Name: ressource; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.ressource (
    id integer NOT NULL,
    referent_id integer,
    name character varying(255) NOT NULL,
    type character varying(255) NOT NULL,
    state character varying(255) DEFAULT NULL::character varying,
    semestre character varying(10) NOT NULL,
    parent_ressource_id integer,
    heures_semaine integer DEFAULT 0 NOT NULL
);


--
-- Name: ressource_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.ressource_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: ressource_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.ressource_id_seq OWNED BY public.ressource.id;


--
-- Name: ressource_matiere; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.ressource_matiere (
    ressource_id integer NOT NULL,
    matiere_id integer NOT NULL
);


--
-- Name: ressource_semaine; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.ressource_semaine (
    id integer NOT NULL,
    ressource_id integer,
    semaine character varying(255) NOT NULL,
    cm double precision NOT NULL,
    td double precision NOT NULL,
    tp double precision NOT NULL,
    ds double precision NOT NULL,
    sae double precision NOT NULL
);


--
-- Name: ressource_semaine_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.ressource_semaine_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: ressource_semaine_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.ressource_semaine_id_seq OWNED BY public.ressource_semaine.id;


--
-- Name: type_enseignant; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.type_enseignant (
    id integer NOT NULL,
    type character varying(255) DEFAULT NULL::character varying
);


--
-- Name: type_enseignant_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.type_enseignant_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: type_enseignant_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.type_enseignant_id_seq OWNED BY public.type_enseignant.id;


--
-- Name: user; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public."user" (
    id integer NOT NULL,
    username character varying(255),
    password character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    roles json NOT NULL,
    first_name character varying(255) NOT NULL,
    last_name character varying(255) NOT NULL
);


--
-- Name: user_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.user_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: user_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.user_id_seq OWNED BY public."user".id;


--
-- Name: creneau id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.creneau ALTER COLUMN id SET DEFAULT nextval('public.creneau_id_seq'::regclass);


--
-- Name: enseignant id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.enseignant ALTER COLUMN id SET DEFAULT nextval('public.enseignant_id_seq'::regclass);


--
-- Name: matiere id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.matiere ALTER COLUMN id SET DEFAULT nextval('public.matiere_id_seq'::regclass);


--
-- Name: messenger_messages id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.messenger_messages ALTER COLUMN id SET DEFAULT nextval('public.messenger_messages_id_seq'::regclass);


--
-- Name: promotion id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.promotion ALTER COLUMN id SET DEFAULT nextval('public.promotion_id_seq'::regclass);


--
-- Name: ressource id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ressource ALTER COLUMN id SET DEFAULT nextval('public.ressource_id_seq'::regclass);


--
-- Name: ressource_semaine id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ressource_semaine ALTER COLUMN id SET DEFAULT nextval('public.ressource_semaine_id_seq'::regclass);


--
-- Name: type_enseignant id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.type_enseignant ALTER COLUMN id SET DEFAULT nextval('public.type_enseignant_id_seq'::regclass);


--
-- Name: user id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."user" ALTER COLUMN id SET DEFAULT nextval('public.user_id_seq'::regclass);


--
-- Data for Name: creneau; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.creneau (id, matiere_id, enseignant_id, ressource_id, start_date, duree, promotion_id, type, end_date) FROM stdin;
82	\N	\N	\N	2025-02-03 00:00:00	\N	\N	vacances	2025-02-07 00:00:00
87	\N	\N	\N	2025-03-04 00:00:00	\N	\N	jour-non-ouvre	2025-03-04 00:00:00
90	\N	\N	\N	2025-03-16 00:00:00	\N	\N	jour-non-ouvre	2025-03-16 00:00:00
91	\N	\N	\N	2025-03-20 00:00:00	\N	4	jour-non-ouvre	2025-03-20 00:00:00
92	\N	\N	\N	2025-03-13 00:00:00	\N	6	jour-non-ouvre	2025-03-13 00:00:00
\.


--
-- Data for Name: doctrine_migration_versions; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.doctrine_migration_versions (version, executed_at, execution_time) FROM stdin;
DoctrineMigrations\\Version20250112165344	2025-01-12 16:53:58	38
DoctrineMigrations\\Version20250116174120	2025-01-16 17:41:33	36
DoctrineMigrations\\Version20250116175628	2025-01-16 17:56:36	22
DoctrineMigrations\\Version20250116180614	2025-01-16 18:06:24	23
DoctrineMigrations\\Version20250116181037	2025-01-16 18:10:45	18
DoctrineMigrations\\Version20250120223002	2025-01-20 22:30:09	5
DoctrineMigrations\\Version20250204104447	2025-02-04 10:46:28	12
DoctrineMigrations\\Version20250209094151	2025-02-09 09:42:56	15
DoctrineMigrations\\Version20250209094701	2025-02-09 09:49:44	5
DoctrineMigrations\\Version20250209095720	2025-02-09 09:58:21	4
DoctrineMigrations\\Version20250211202120	2025-02-11 20:21:32	6
DoctrineMigrations\\Version20250308150701	2025-03-08 15:07:20	2
DoctrineMigrations\\Version20250308152650	2025-03-08 15:26:59	17
DoctrineMigrations\\Version20250315104739	2025-03-15 10:48:07	187
DoctrineMigrations\\Version20250315105706	2025-03-15 10:57:23	5
DoctrineMigrations\\Version20250315131541	2025-03-15 13:16:32	645
DoctrineMigrations\\Version20250315135113	2025-03-15 13:51:36	1048
DoctrineMigrations\\Version20250315141531	2025-03-15 14:15:54	173
DoctrineMigrations\\Version20250315143102	2025-03-15 14:31:22	503
DoctrineMigrations\\Version20250318083522	2025-03-18 08:35:42	226
\.


--
-- Data for Name: enseignant; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.enseignant (id, utilisateur_id, type_enseignant_id) FROM stdin;
1	12	2
\.


--
-- Data for Name: matiere; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.matiere (id, enseignant_id, name, volume_horaire) FROM stdin;
1	\N	Mathématiques	50
\.


--
-- Data for Name: messenger_messages; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.messenger_messages (id, body, headers, queue_name, created_at, available_at, delivered_at) FROM stdin;
\.


--
-- Data for Name: promotion; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.promotion (id, year_level) FROM stdin;
1	1
2	2
3	3
4	1A
5	2A
6	3A
\.


--
-- Data for Name: ressource; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.ressource (id, referent_id, name, type, state, semestre, parent_ressource_id, heures_semaine) FROM stdin;
2	1	R 1.01	en cours	encours	S1	\N	0
\.


--
-- Data for Name: ressource_matiere; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.ressource_matiere (ressource_id, matiere_id) FROM stdin;
\.


--
-- Data for Name: ressource_semaine; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.ressource_semaine (id, ressource_id, semaine, cm, td, tp, ds, sae) FROM stdin;
1	2	1	3	3	2	1	2
2	2	2	3	4	1	0	0
3	2	3	0	1	0	0	0
4	2	4	0	0	1	0	0
5	2	24 February 2025	5	0	0	0	0
6	2	03 March 2025	5	0	0	0	0
7	2	10 March 2025	0	0	3	0	0
8	2	17 March 2025	0	2	0	0	0
9	2	24 March 2025	0	0	0	3	0
10	2	31 March 2025	0	0	4	1	0
\.


--
-- Data for Name: type_enseignant; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.type_enseignant (id, type) FROM stdin;
1	Professeur
2	Référent
3	Vacataire
\.


--
-- Data for Name: user; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public."user" (id, username, password, email, roles, first_name, last_name) FROM stdin;
1	hugo	$2y$13$cVyTGBe8AsOq8d2AQPRQ9OCgoNZgxoQ1WPvaazEhhICdhPjTS1SJW	hgonzalez3004@gmail.com	["ROLE_USER"]	Hugo	Gonzalez
3	testuser	$2y$13$cVyTGBe8AsOq8d2AQPRQ9OCgoNZgxoQ1WPvaazEhhICdhPjTS1SJW	test@example.com	["ROLE_USER"]	Test	User
7	\N	$2y$13$fk7.PB44TAHLB2GiaJmauOvu5NIHJyTez2gH93V7jMuSTaEQkx9fm	test2@example.com	["ROLE_USER"]	John	Doe
9	\N	$2y$13$XDk8byNYzNsIekzg2Od7PeoKi5oH6dTLlSqxjqwkXfaIl9RHbsrcC	admin@example.com	["ROLE_ADMIN"]	Admin	User
11	\N	$2y$13$ll3lqUrawjiYLM0x1M0P7.au1K6l9luTJ14bOgHQEWFugr1loB1wC	test3@example.com	["ROLE_ADMIN"]	Test	User
12	\N	$2y$13$rZPdyNztj6H10e.YJzvtAOH3BkBdIUKPbmQ1RZ1p/gjopaIxD/uxO	referent1@example.com	["ROLE_PROF_REFERENT"]	Jean	Dupont
\.


--
-- Name: creneau_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.creneau_id_seq', 92, true);


--
-- Name: enseignant_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.enseignant_id_seq', 1, true);


--
-- Name: matiere_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.matiere_id_seq', 1, true);


--
-- Name: messenger_messages_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.messenger_messages_id_seq', 1, false);


--
-- Name: promotion_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.promotion_id_seq', 6, true);


--
-- Name: ressource_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.ressource_id_seq', 4, true);


--
-- Name: ressource_semaine_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.ressource_semaine_id_seq', 13, true);


--
-- Name: type_enseignant_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.type_enseignant_id_seq', 3, true);


--
-- Name: user_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.user_id_seq', 12, true);


--
-- Name: creneau creneau_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.creneau
    ADD CONSTRAINT creneau_pkey PRIMARY KEY (id);


--
-- Name: doctrine_migration_versions doctrine_migration_versions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.doctrine_migration_versions
    ADD CONSTRAINT doctrine_migration_versions_pkey PRIMARY KEY (version);


--
-- Name: enseignant enseignant_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.enseignant
    ADD CONSTRAINT enseignant_pkey PRIMARY KEY (id);


--
-- Name: matiere matiere_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.matiere
    ADD CONSTRAINT matiere_pkey PRIMARY KEY (id);


--
-- Name: messenger_messages messenger_messages_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.messenger_messages
    ADD CONSTRAINT messenger_messages_pkey PRIMARY KEY (id);


--
-- Name: promotion promotion_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.promotion
    ADD CONSTRAINT promotion_pkey PRIMARY KEY (id);


--
-- Name: ressource_matiere ressource_matiere_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ressource_matiere
    ADD CONSTRAINT ressource_matiere_pkey PRIMARY KEY (ressource_id, matiere_id);


--
-- Name: ressource ressource_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ressource
    ADD CONSTRAINT ressource_pkey PRIMARY KEY (id);


--
-- Name: ressource_semaine ressource_semaine_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ressource_semaine
    ADD CONSTRAINT ressource_semaine_pkey PRIMARY KEY (id);


--
-- Name: type_enseignant type_enseignant_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.type_enseignant
    ADD CONSTRAINT type_enseignant_pkey PRIMARY KEY (id);


--
-- Name: user user_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public."user"
    ADD CONSTRAINT user_pkey PRIMARY KEY (id);


--
-- Name: idx_75ea56e016ba31db; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_75ea56e016ba31db ON public.messenger_messages USING btree (delivered_at);


--
-- Name: idx_75ea56e0e3bd61ce; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_75ea56e0e3bd61ce ON public.messenger_messages USING btree (available_at);


--
-- Name: idx_75ea56e0fb7336f0; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_75ea56e0fb7336f0 ON public.messenger_messages USING btree (queue_name);


--
-- Name: idx_81a72fa194b84bf5; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_81a72fa194b84bf5 ON public.enseignant USING btree (type_enseignant_id);


--
-- Name: idx_9014574ae455fcc0; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_9014574ae455fcc0 ON public.matiere USING btree (enseignant_id);


--
-- Name: idx_939f454435e47e35; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_939f454435e47e35 ON public.ressource USING btree (referent_id);


--
-- Name: idx_939f4544ac028804; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_939f4544ac028804 ON public.ressource USING btree (parent_ressource_id);


--
-- Name: idx_ce770508fc6cd52a; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_ce770508fc6cd52a ON public.ressource_semaine USING btree (ressource_id);


--
-- Name: idx_f9668b5f139df194; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_f9668b5f139df194 ON public.creneau USING btree (promotion_id);


--
-- Name: idx_f9668b5fe455fcc0; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_f9668b5fe455fcc0 ON public.creneau USING btree (enseignant_id);


--
-- Name: idx_f9668b5ff46cd258; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_f9668b5ff46cd258 ON public.creneau USING btree (matiere_id);


--
-- Name: idx_f9668b5ffc6cd52a; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_f9668b5ffc6cd52a ON public.creneau USING btree (ressource_id);


--
-- Name: uniq_81a72fa1fb88e14f; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX uniq_81a72fa1fb88e14f ON public.enseignant USING btree (utilisateur_id);


--
-- Name: uniq_8d93d649e7927c74; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX uniq_8d93d649e7927c74 ON public."user" USING btree (email);


--
-- Name: uniq_8d93d649f85e0677; Type: INDEX; Schema: public; Owner: -
--

CREATE UNIQUE INDEX uniq_8d93d649f85e0677 ON public."user" USING btree (username);


--
-- Name: messenger_messages notify_trigger; Type: TRIGGER; Schema: public; Owner: -
--

CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON public.messenger_messages FOR EACH ROW EXECUTE FUNCTION public.notify_messenger_messages();


--
-- Name: enseignant fk_81a72fa194b84bf5; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.enseignant
    ADD CONSTRAINT fk_81a72fa194b84bf5 FOREIGN KEY (type_enseignant_id) REFERENCES public.type_enseignant(id);


--
-- Name: enseignant fk_81a72fa1fb88e14f; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.enseignant
    ADD CONSTRAINT fk_81a72fa1fb88e14f FOREIGN KEY (utilisateur_id) REFERENCES public."user"(id);


--
-- Name: matiere fk_9014574ae455fcc0; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.matiere
    ADD CONSTRAINT fk_9014574ae455fcc0 FOREIGN KEY (enseignant_id) REFERENCES public."user"(id);


--
-- Name: ressource fk_939f454435e47e35; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ressource
    ADD CONSTRAINT fk_939f454435e47e35 FOREIGN KEY (referent_id) REFERENCES public."user"(id);


--
-- Name: ressource fk_939f4544ac028804; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ressource
    ADD CONSTRAINT fk_939f4544ac028804 FOREIGN KEY (parent_ressource_id) REFERENCES public.ressource(id);


--
-- Name: ressource_semaine fk_ce770508fc6cd52a; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ressource_semaine
    ADD CONSTRAINT fk_ce770508fc6cd52a FOREIGN KEY (ressource_id) REFERENCES public.ressource(id);


--
-- Name: creneau fk_f9668b5f139df194; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.creneau
    ADD CONSTRAINT fk_f9668b5f139df194 FOREIGN KEY (promotion_id) REFERENCES public.promotion(id);


--
-- Name: creneau fk_f9668b5fe455fcc0; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.creneau
    ADD CONSTRAINT fk_f9668b5fe455fcc0 FOREIGN KEY (enseignant_id) REFERENCES public."user"(id);


--
-- Name: creneau fk_f9668b5ff46cd258; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.creneau
    ADD CONSTRAINT fk_f9668b5ff46cd258 FOREIGN KEY (matiere_id) REFERENCES public.matiere(id);


--
-- Name: creneau fk_f9668b5ffc6cd52a; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.creneau
    ADD CONSTRAINT fk_f9668b5ffc6cd52a FOREIGN KEY (ressource_id) REFERENCES public.ressource(id);


--
-- Name: ressource_matiere ressource_matiere_matiere_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ressource_matiere
    ADD CONSTRAINT ressource_matiere_matiere_id_fkey FOREIGN KEY (matiere_id) REFERENCES public.matiere(id) ON DELETE CASCADE;


--
-- Name: ressource_matiere ressource_matiere_ressource_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.ressource_matiere
    ADD CONSTRAINT ressource_matiere_ressource_id_fkey FOREIGN KEY (ressource_id) REFERENCES public.ressource(id) ON DELETE CASCADE;


--
-- Name: DEFAULT PRIVILEGES FOR SEQUENCES; Type: DEFAULT ACL; Schema: -; Owner: -
--

ALTER DEFAULT PRIVILEGES FOR ROLE postgres GRANT ALL ON SEQUENCES TO hugo;


--
-- Name: DEFAULT PRIVILEGES FOR TYPES; Type: DEFAULT ACL; Schema: -; Owner: -
--

ALTER DEFAULT PRIVILEGES FOR ROLE postgres GRANT ALL ON TYPES TO hugo;


--
-- Name: DEFAULT PRIVILEGES FOR FUNCTIONS; Type: DEFAULT ACL; Schema: -; Owner: -
--

ALTER DEFAULT PRIVILEGES FOR ROLE postgres GRANT ALL ON FUNCTIONS TO hugo;


--
-- Name: DEFAULT PRIVILEGES FOR TABLES; Type: DEFAULT ACL; Schema: -; Owner: -
--

ALTER DEFAULT PRIVILEGES FOR ROLE postgres GRANT ALL ON TABLES TO hugo;


--
-- PostgreSQL database dump complete
--

