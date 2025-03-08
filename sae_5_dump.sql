--
-- PostgreSQL database dump
--

-- Dumped from database version 14.15 (Homebrew)
-- Dumped by pg_dump version 14.15 (Homebrew)

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
-- Name: notify_messenger_messages(); Type: FUNCTION; Schema: public; Owner: app
--

CREATE FUNCTION public.notify_messenger_messages() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
            BEGIN
                PERFORM pg_notify('messenger_messages', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$;


ALTER FUNCTION public.notify_messenger_messages() OWNER TO app;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: creneau; Type: TABLE; Schema: public; Owner: app
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


ALTER TABLE public.creneau OWNER TO app;

--
-- Name: creneau_id_seq; Type: SEQUENCE; Schema: public; Owner: app
--

CREATE SEQUENCE public.creneau_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.creneau_id_seq OWNER TO app;

--
-- Name: creneau_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: app
--

ALTER SEQUENCE public.creneau_id_seq OWNED BY public.creneau.id;


--
-- Name: doctrine_migration_versions; Type: TABLE; Schema: public; Owner: app
--

CREATE TABLE public.doctrine_migration_versions (
    version character varying(191) NOT NULL,
    executed_at timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    execution_time integer
);


ALTER TABLE public.doctrine_migration_versions OWNER TO app;

--
-- Name: matiere; Type: TABLE; Schema: public; Owner: app
--

CREATE TABLE public.matiere (
    id integer NOT NULL,
    enseignant_id integer,
    name character varying(255) NOT NULL,
    volume_horaire integer NOT NULL
);


ALTER TABLE public.matiere OWNER TO app;

--
-- Name: matiere_id_seq; Type: SEQUENCE; Schema: public; Owner: app
--

CREATE SEQUENCE public.matiere_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.matiere_id_seq OWNER TO app;

--
-- Name: matiere_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: app
--

ALTER SEQUENCE public.matiere_id_seq OWNED BY public.matiere.id;


--
-- Name: messenger_messages; Type: TABLE; Schema: public; Owner: app
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


ALTER TABLE public.messenger_messages OWNER TO app;

--
-- Name: COLUMN messenger_messages.created_at; Type: COMMENT; Schema: public; Owner: app
--

COMMENT ON COLUMN public.messenger_messages.created_at IS '(DC2Type:datetime_immutable)';


--
-- Name: COLUMN messenger_messages.available_at; Type: COMMENT; Schema: public; Owner: app
--

COMMENT ON COLUMN public.messenger_messages.available_at IS '(DC2Type:datetime_immutable)';


--
-- Name: COLUMN messenger_messages.delivered_at; Type: COMMENT; Schema: public; Owner: app
--

COMMENT ON COLUMN public.messenger_messages.delivered_at IS '(DC2Type:datetime_immutable)';


--
-- Name: messenger_messages_id_seq; Type: SEQUENCE; Schema: public; Owner: app
--

CREATE SEQUENCE public.messenger_messages_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.messenger_messages_id_seq OWNER TO app;

--
-- Name: messenger_messages_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: app
--

ALTER SEQUENCE public.messenger_messages_id_seq OWNED BY public.messenger_messages.id;


--
-- Name: promotion; Type: TABLE; Schema: public; Owner: app
--

CREATE TABLE public.promotion (
    id integer NOT NULL,
    year_level integer NOT NULL
);


ALTER TABLE public.promotion OWNER TO app;

--
-- Name: promotion_id_seq; Type: SEQUENCE; Schema: public; Owner: app
--

CREATE SEQUENCE public.promotion_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.promotion_id_seq OWNER TO app;

--
-- Name: promotion_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: app
--

ALTER SEQUENCE public.promotion_id_seq OWNED BY public.promotion.id;


--
-- Name: ressource; Type: TABLE; Schema: public; Owner: app
--

CREATE TABLE public.ressource (
    id integer NOT NULL,
    referent_id integer,
    name character varying(255) NOT NULL,
    type character varying(255) NOT NULL,
    state character varying(255) DEFAULT NULL::character varying
);


ALTER TABLE public.ressource OWNER TO app;

--
-- Name: ressource_id_seq; Type: SEQUENCE; Schema: public; Owner: app
--

CREATE SEQUENCE public.ressource_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.ressource_id_seq OWNER TO app;

--
-- Name: ressource_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: app
--

ALTER SEQUENCE public.ressource_id_seq OWNED BY public.ressource.id;


--
-- Name: user; Type: TABLE; Schema: public; Owner: app
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


ALTER TABLE public."user" OWNER TO app;

--
-- Name: user_id_seq; Type: SEQUENCE; Schema: public; Owner: app
--

CREATE SEQUENCE public.user_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.user_id_seq OWNER TO app;

--
-- Name: user_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: app
--

ALTER SEQUENCE public.user_id_seq OWNED BY public."user".id;


--
-- Name: creneau id; Type: DEFAULT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.creneau ALTER COLUMN id SET DEFAULT nextval('public.creneau_id_seq'::regclass);


--
-- Name: matiere id; Type: DEFAULT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.matiere ALTER COLUMN id SET DEFAULT nextval('public.matiere_id_seq'::regclass);


--
-- Name: messenger_messages id; Type: DEFAULT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.messenger_messages ALTER COLUMN id SET DEFAULT nextval('public.messenger_messages_id_seq'::regclass);


--
-- Name: promotion id; Type: DEFAULT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.promotion ALTER COLUMN id SET DEFAULT nextval('public.promotion_id_seq'::regclass);


--
-- Name: ressource id; Type: DEFAULT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.ressource ALTER COLUMN id SET DEFAULT nextval('public.ressource_id_seq'::regclass);


--
-- Name: user id; Type: DEFAULT; Schema: public; Owner: app
--

ALTER TABLE ONLY public."user" ALTER COLUMN id SET DEFAULT nextval('public.user_id_seq'::regclass);


--
-- Data for Name: creneau; Type: TABLE DATA; Schema: public; Owner: app
--

COPY public.creneau (id, matiere_id, enseignant_id, ressource_id, start_date, duree, promotion_id, type, end_date) FROM stdin;
82	\N	\N	\N	2025-02-03 00:00:00	\N	\N	vacances	2025-02-07 00:00:00
\.


--
-- Data for Name: doctrine_migration_versions; Type: TABLE DATA; Schema: public; Owner: app
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
\.


--
-- Data for Name: matiere; Type: TABLE DATA; Schema: public; Owner: app
--

COPY public.matiere (id, enseignant_id, name, volume_horaire) FROM stdin;
\.


--
-- Data for Name: messenger_messages; Type: TABLE DATA; Schema: public; Owner: app
--

COPY public.messenger_messages (id, body, headers, queue_name, created_at, available_at, delivered_at) FROM stdin;
\.


--
-- Data for Name: promotion; Type: TABLE DATA; Schema: public; Owner: app
--

COPY public.promotion (id, year_level) FROM stdin;
\.


--
-- Data for Name: ressource; Type: TABLE DATA; Schema: public; Owner: app
--

COPY public.ressource (id, referent_id, name, type, state) FROM stdin;
\.


--
-- Data for Name: user; Type: TABLE DATA; Schema: public; Owner: app
--

COPY public."user" (id, username, password, email, roles, first_name, last_name) FROM stdin;
1	hugo	$2y$13$cVyTGBe8AsOq8d2AQPRQ9OCgoNZgxoQ1WPvaazEhhICdhPjTS1SJW	hgonzalez3004@gmail.com	["ROLE_USER"]	Hugo	Gonzalez
3	testuser	$2y$13$cVyTGBe8AsOq8d2AQPRQ9OCgoNZgxoQ1WPvaazEhhICdhPjTS1SJW	test@example.com	["ROLE_USER"]	Test	User
7	\N	$2y$13$fk7.PB44TAHLB2GiaJmauOvu5NIHJyTez2gH93V7jMuSTaEQkx9fm	test2@example.com	["ROLE_USER"]	John	Doe
9	\N	$2y$13$XDk8byNYzNsIekzg2Od7PeoKi5oH6dTLlSqxjqwkXfaIl9RHbsrcC	admin@example.com	["ROLE_ADMIN"]	Admin	User
\.


--
-- Name: creneau_id_seq; Type: SEQUENCE SET; Schema: public; Owner: app
--

SELECT pg_catalog.setval('public.creneau_id_seq', 86, true);


--
-- Name: matiere_id_seq; Type: SEQUENCE SET; Schema: public; Owner: app
--

SELECT pg_catalog.setval('public.matiere_id_seq', 1, false);


--
-- Name: messenger_messages_id_seq; Type: SEQUENCE SET; Schema: public; Owner: app
--

SELECT pg_catalog.setval('public.messenger_messages_id_seq', 1, false);


--
-- Name: promotion_id_seq; Type: SEQUENCE SET; Schema: public; Owner: app
--

SELECT pg_catalog.setval('public.promotion_id_seq', 1, false);


--
-- Name: ressource_id_seq; Type: SEQUENCE SET; Schema: public; Owner: app
--

SELECT pg_catalog.setval('public.ressource_id_seq', 1, false);


--
-- Name: user_id_seq; Type: SEQUENCE SET; Schema: public; Owner: app
--

SELECT pg_catalog.setval('public.user_id_seq', 9, true);


--
-- Name: creneau creneau_pkey; Type: CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.creneau
    ADD CONSTRAINT creneau_pkey PRIMARY KEY (id);


--
-- Name: doctrine_migration_versions doctrine_migration_versions_pkey; Type: CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.doctrine_migration_versions
    ADD CONSTRAINT doctrine_migration_versions_pkey PRIMARY KEY (version);


--
-- Name: matiere matiere_pkey; Type: CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.matiere
    ADD CONSTRAINT matiere_pkey PRIMARY KEY (id);


--
-- Name: messenger_messages messenger_messages_pkey; Type: CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.messenger_messages
    ADD CONSTRAINT messenger_messages_pkey PRIMARY KEY (id);


--
-- Name: promotion promotion_pkey; Type: CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.promotion
    ADD CONSTRAINT promotion_pkey PRIMARY KEY (id);


--
-- Name: ressource ressource_pkey; Type: CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.ressource
    ADD CONSTRAINT ressource_pkey PRIMARY KEY (id);


--
-- Name: user user_pkey; Type: CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public."user"
    ADD CONSTRAINT user_pkey PRIMARY KEY (id);


--
-- Name: idx_75ea56e016ba31db; Type: INDEX; Schema: public; Owner: app
--

CREATE INDEX idx_75ea56e016ba31db ON public.messenger_messages USING btree (delivered_at);


--
-- Name: idx_75ea56e0e3bd61ce; Type: INDEX; Schema: public; Owner: app
--

CREATE INDEX idx_75ea56e0e3bd61ce ON public.messenger_messages USING btree (available_at);


--
-- Name: idx_75ea56e0fb7336f0; Type: INDEX; Schema: public; Owner: app
--

CREATE INDEX idx_75ea56e0fb7336f0 ON public.messenger_messages USING btree (queue_name);


--
-- Name: idx_9014574ae455fcc0; Type: INDEX; Schema: public; Owner: app
--

CREATE INDEX idx_9014574ae455fcc0 ON public.matiere USING btree (enseignant_id);


--
-- Name: idx_939f454435e47e35; Type: INDEX; Schema: public; Owner: app
--

CREATE INDEX idx_939f454435e47e35 ON public.ressource USING btree (referent_id);


--
-- Name: idx_f9668b5f139df194; Type: INDEX; Schema: public; Owner: app
--

CREATE INDEX idx_f9668b5f139df194 ON public.creneau USING btree (promotion_id);


--
-- Name: idx_f9668b5fe455fcc0; Type: INDEX; Schema: public; Owner: app
--

CREATE INDEX idx_f9668b5fe455fcc0 ON public.creneau USING btree (enseignant_id);


--
-- Name: idx_f9668b5ff46cd258; Type: INDEX; Schema: public; Owner: app
--

CREATE INDEX idx_f9668b5ff46cd258 ON public.creneau USING btree (matiere_id);


--
-- Name: idx_f9668b5ffc6cd52a; Type: INDEX; Schema: public; Owner: app
--

CREATE INDEX idx_f9668b5ffc6cd52a ON public.creneau USING btree (ressource_id);


--
-- Name: uniq_8d93d649e7927c74; Type: INDEX; Schema: public; Owner: app
--

CREATE UNIQUE INDEX uniq_8d93d649e7927c74 ON public."user" USING btree (email);


--
-- Name: uniq_8d93d649f85e0677; Type: INDEX; Schema: public; Owner: app
--

CREATE UNIQUE INDEX uniq_8d93d649f85e0677 ON public."user" USING btree (username);


--
-- Name: messenger_messages notify_trigger; Type: TRIGGER; Schema: public; Owner: app
--

CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON public.messenger_messages FOR EACH ROW EXECUTE FUNCTION public.notify_messenger_messages();


--
-- Name: matiere fk_9014574ae455fcc0; Type: FK CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.matiere
    ADD CONSTRAINT fk_9014574ae455fcc0 FOREIGN KEY (enseignant_id) REFERENCES public."user"(id);


--
-- Name: ressource fk_939f454435e47e35; Type: FK CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.ressource
    ADD CONSTRAINT fk_939f454435e47e35 FOREIGN KEY (referent_id) REFERENCES public."user"(id);


--
-- Name: creneau fk_f9668b5f139df194; Type: FK CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.creneau
    ADD CONSTRAINT fk_f9668b5f139df194 FOREIGN KEY (promotion_id) REFERENCES public.promotion(id);


--
-- Name: creneau fk_f9668b5fe455fcc0; Type: FK CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.creneau
    ADD CONSTRAINT fk_f9668b5fe455fcc0 FOREIGN KEY (enseignant_id) REFERENCES public."user"(id);


--
-- Name: creneau fk_f9668b5ff46cd258; Type: FK CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.creneau
    ADD CONSTRAINT fk_f9668b5ff46cd258 FOREIGN KEY (matiere_id) REFERENCES public.matiere(id);


--
-- Name: creneau fk_f9668b5ffc6cd52a; Type: FK CONSTRAINT; Schema: public; Owner: app
--

ALTER TABLE ONLY public.creneau
    ADD CONSTRAINT fk_f9668b5ffc6cd52a FOREIGN KEY (ressource_id) REFERENCES public.ressource(id);


--
-- PostgreSQL database dump complete
--

