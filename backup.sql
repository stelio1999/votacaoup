--
-- PostgreSQL database dump
--

\restrict VoLz7l59NdM1ryij1TjzG8RnI4IovwjpLhgGRazkNGBDRHY3OsygVMVAOJNRf3h

-- Dumped from database version 18.3
-- Dumped by pg_dump version 18.3

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: candidatos; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.candidatos (
    id bigint NOT NULL,
    user_id bigint NOT NULL,
    eleicao_id bigint NOT NULL,
    cargo_id bigint NOT NULL,
    numero_candidato character varying(255) NOT NULL,
    proposta text,
    curriculo text,
    foto character varying(255),
    video_url character varying(255),
    website character varying(255),
    aprovado boolean DEFAULT false NOT NULL,
    motivo_reprovacao text,
    votos_recebidos integer DEFAULT 0 NOT NULL,
    percentual_votos numeric(5,2) DEFAULT '0'::numeric NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


ALTER TABLE public.candidatos OWNER TO postgres;

--
-- Name: candidatos_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.candidatos_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.candidatos_id_seq OWNER TO postgres;

--
-- Name: candidatos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.candidatos_id_seq OWNED BY public.candidatos.id;


--
-- Name: cargos; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.cargos (
    id bigint NOT NULL,
    nome character varying(255) NOT NULL,
    descricao text,
    categoria character varying(255) NOT NULL,
    mandato_meses integer DEFAULT 24 NOT NULL,
    responsabilidades text,
    requisitos text,
    beneficios text,
    ativo boolean DEFAULT true NOT NULL,
    ordem integer DEFAULT 0 NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


ALTER TABLE public.cargos OWNER TO postgres;

--
-- Name: cargos_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.cargos_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.cargos_id_seq OWNER TO postgres;

--
-- Name: cargos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.cargos_id_seq OWNED BY public.cargos.id;


--
-- Name: categorias; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.categorias (
    id bigint NOT NULL,
    nome character varying(255) NOT NULL,
    slug character varying(255) NOT NULL,
    descricao text,
    cor character varying(255) DEFAULT '#6c757d'::character varying NOT NULL,
    icone character varying(255) DEFAULT 'fas fa-user'::character varying NOT NULL,
    ordem integer DEFAULT 0 NOT NULL,
    ativo boolean DEFAULT true NOT NULL,
    configuracoes json,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


ALTER TABLE public.categorias OWNER TO postgres;

--
-- Name: categorias_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.categorias_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.categorias_id_seq OWNER TO postgres;

--
-- Name: categorias_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.categorias_id_seq OWNED BY public.categorias.id;


--
-- Name: eleicoes; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.eleicoes (
    id bigint NOT NULL,
    titulo character varying(255) NOT NULL,
    slug character varying(255) NOT NULL,
    descricao text,
    cargo_id bigint NOT NULL,
    data_inicio timestamp(0) without time zone NOT NULL,
    data_fim timestamp(0) without time zone NOT NULL,
    status character varying(255) DEFAULT 'agendada'::character varying NOT NULL,
    total_eleitores integer DEFAULT 0 NOT NULL,
    votos_registrados integer DEFAULT 0 NOT NULL,
    votos_validos integer DEFAULT 0 NOT NULL,
    votos_nulos integer DEFAULT 0 NOT NULL,
    votos_brancos integer DEFAULT 0 NOT NULL,
    percentual_conclusao numeric(5,2) DEFAULT '0'::numeric NOT NULL,
    percentual_participacao numeric(5,2) DEFAULT '0'::numeric NOT NULL,
    observacoes text,
    regras text,
    resultado_publico boolean DEFAULT true NOT NULL,
    voto_anonimo boolean DEFAULT true NOT NULL,
    permite_voto_branco boolean DEFAULT true NOT NULL,
    permite_voto_nulo boolean DEFAULT true NOT NULL,
    duracao_votacao_horas integer,
    limite_tentativas integer DEFAULT 3 NOT NULL,
    configuracoes json,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    CONSTRAINT eleicoes_status_check CHECK (((status)::text = ANY ((ARRAY['agendada'::character varying, 'ativa'::character varying, 'concluida'::character varying, 'cancelada'::character varying, 'suspensa'::character varying])::text[])))
);


ALTER TABLE public.eleicoes OWNER TO postgres;

--
-- Name: eleicoes_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.eleicoes_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.eleicoes_id_seq OWNER TO postgres;

--
-- Name: eleicoes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.eleicoes_id_seq OWNED BY public.eleicoes.id;


--
-- Name: failed_jobs; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.failed_jobs (
    id bigint NOT NULL,
    uuid character varying(255) NOT NULL,
    connection text NOT NULL,
    queue text NOT NULL,
    payload text NOT NULL,
    exception text NOT NULL,
    failed_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


ALTER TABLE public.failed_jobs OWNER TO postgres;

--
-- Name: failed_jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.failed_jobs_id_seq OWNER TO postgres;

--
-- Name: failed_jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;


--
-- Name: logs; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.logs (
    id bigint NOT NULL,
    user_id bigint,
    acao character varying(255) NOT NULL,
    modulo character varying(255),
    descricao text NOT NULL,
    ip_address character varying(255) NOT NULL,
    user_agent character varying(255),
    metodo character varying(255),
    url character varying(255),
    dados_anteriores json,
    dados_novos json,
    dados_alterados json,
    severidade character varying(255) DEFAULT 'info'::character varying NOT NULL,
    tipo character varying(255) DEFAULT 'sistema'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.logs OWNER TO postgres;

--
-- Name: logs_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.logs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.logs_id_seq OWNER TO postgres;

--
-- Name: logs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.logs_id_seq OWNED BY public.logs.id;


--
-- Name: migrations; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


ALTER TABLE public.migrations OWNER TO postgres;

--
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.migrations_id_seq OWNER TO postgres;

--
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- Name: password_reset_tokens; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.password_reset_tokens (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);


ALTER TABLE public.password_reset_tokens OWNER TO postgres;

--
-- Name: personal_access_tokens; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.personal_access_tokens (
    id bigint NOT NULL,
    tokenable_type character varying(255) NOT NULL,
    tokenable_id bigint NOT NULL,
    name character varying(255) NOT NULL,
    token character varying(64) NOT NULL,
    abilities text,
    last_used_at timestamp(0) without time zone,
    expires_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.personal_access_tokens OWNER TO postgres;

--
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.personal_access_tokens_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.personal_access_tokens_id_seq OWNER TO postgres;

--
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.personal_access_tokens_id_seq OWNED BY public.personal_access_tokens.id;


--
-- Name: resultados; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.resultados (
    id bigint NOT NULL,
    eleicao_id bigint NOT NULL,
    candidato_id bigint,
    tipo_resultado character varying(255) DEFAULT 'candidato'::character varying NOT NULL,
    total_votos integer DEFAULT 0 NOT NULL,
    percentual numeric(5,2) DEFAULT '0'::numeric NOT NULL,
    votos_validos integer DEFAULT 0 NOT NULL,
    votos_nulos integer DEFAULT 0 NOT NULL,
    votos_brancos integer DEFAULT 0 NOT NULL,
    eleito boolean DEFAULT false NOT NULL,
    posicao integer,
    estatisticas json,
    distribuicao_temporal json,
    distribuicao_geografica json,
    observacoes text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


ALTER TABLE public.resultados OWNER TO postgres;

--
-- Name: resultados_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.resultados_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.resultados_id_seq OWNER TO postgres;

--
-- Name: resultados_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.resultados_id_seq OWNED BY public.resultados.id;


--
-- Name: users; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.users (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    password character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    role character varying(255) DEFAULT 'eleitor'::character varying NOT NULL,
    categoria character varying(255),
    matricula character varying(255),
    curso character varying(255),
    departamento character varying(255),
    telefone character varying(255),
    ativo boolean DEFAULT true NOT NULL,
    ultimo_acesso timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    preferencias json,
    foto character varying(255)
);


ALTER TABLE public.users OWNER TO postgres;

--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.users_id_seq OWNER TO postgres;

--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- Name: votos; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.votos (
    id bigint NOT NULL,
    eleicao_id bigint NOT NULL,
    candidato_id bigint,
    user_id bigint NOT NULL,
    hash_voto character varying(255) NOT NULL,
    tipo_voto character varying(255) DEFAULT 'valido'::character varying NOT NULL,
    ip_address character varying(255) NOT NULL,
    user_agent character varying(255),
    dispositivo character varying(255),
    navegador character varying(255),
    sistema_operacional character varying(255),
    latitude numeric(10,8),
    longitude numeric(11,8),
    cidade character varying(255),
    regiao character varying(255),
    pais character varying(255),
    valido boolean DEFAULT true NOT NULL,
    observacoes text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.votos OWNER TO postgres;

--
-- Name: votos_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.votos_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.votos_id_seq OWNER TO postgres;

--
-- Name: votos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.votos_id_seq OWNED BY public.votos.id;


--
-- Name: candidatos id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.candidatos ALTER COLUMN id SET DEFAULT nextval('public.candidatos_id_seq'::regclass);


--
-- Name: cargos id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cargos ALTER COLUMN id SET DEFAULT nextval('public.cargos_id_seq'::regclass);


--
-- Name: categorias id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.categorias ALTER COLUMN id SET DEFAULT nextval('public.categorias_id_seq'::regclass);


--
-- Name: eleicoes id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.eleicoes ALTER COLUMN id SET DEFAULT nextval('public.eleicoes_id_seq'::regclass);


--
-- Name: failed_jobs id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);


--
-- Name: logs id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.logs ALTER COLUMN id SET DEFAULT nextval('public.logs_id_seq'::regclass);


--
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- Name: personal_access_tokens id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.personal_access_tokens ALTER COLUMN id SET DEFAULT nextval('public.personal_access_tokens_id_seq'::regclass);


--
-- Name: resultados id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.resultados ALTER COLUMN id SET DEFAULT nextval('public.resultados_id_seq'::regclass);


--
-- Name: users id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- Name: votos id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.votos ALTER COLUMN id SET DEFAULT nextval('public.votos_id_seq'::regclass);


--
-- Data for Name: candidatos; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.candidatos (id, user_id, eleicao_id, cargo_id, numero_candidato, proposta, curriculo, foto, video_url, website, aprovado, motivo_reprovacao, votos_recebidos, percentual_votos, created_at, updated_at, deleted_at) FROM stdin;
\.


--
-- Data for Name: cargos; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.cargos (id, nome, descricao, categoria, mandato_meses, responsabilidades, requisitos, beneficios, ativo, ordem, created_at, updated_at, deleted_at) FROM stdin;
\.


--
-- Data for Name: categorias; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.categorias (id, nome, slug, descricao, cor, icone, ordem, ativo, configuracoes, created_at, updated_at, deleted_at) FROM stdin;
1	estudante	estudante	Estudantes da Universidade Pedagógica	#28a745	fas fa-user-graduate	1	t	\N	2026-06-09 18:11:57	2026-06-09 18:11:57	\N
2	docente	docente	Corpo docente da Universidade Pedagógica	#007bff	fas fa-chalkboard-teacher	2	t	\N	2026-06-09 18:11:57	2026-06-09 18:11:57	\N
3	tecnico_administrativo	tecnico-administrativo	Técnicos e funcionários administrativos	#6c757d	fas fa-user-tie	3	t	\N	2026-06-09 18:11:57	2026-06-09 18:11:57	\N
\.


--
-- Data for Name: eleicoes; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.eleicoes (id, titulo, slug, descricao, cargo_id, data_inicio, data_fim, status, total_eleitores, votos_registrados, votos_validos, votos_nulos, votos_brancos, percentual_conclusao, percentual_participacao, observacoes, regras, resultado_publico, voto_anonimo, permite_voto_branco, permite_voto_nulo, duracao_votacao_horas, limite_tentativas, configuracoes, created_at, updated_at, deleted_at) FROM stdin;
\.


--
-- Data for Name: failed_jobs; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.failed_jobs (id, uuid, connection, queue, payload, exception, failed_at) FROM stdin;
\.


--
-- Data for Name: logs; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.logs (id, user_id, acao, modulo, descricao, ip_address, user_agent, metodo, url, dados_anteriores, dados_novos, dados_alterados, severidade, tipo, created_at, updated_at) FROM stdin;
1	1	logout	\N	Logout do sistema	127.0.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36	\N	\N	\N	\N	\N	info	sistema	2026-06-09 18:15:13	2026-06-09 18:15:13
2	1	login	\N	Login no sistema	127.0.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36	\N	\N	\N	\N	\N	info	sistema	2026-06-09 18:15:23	2026-06-09 18:15:23
3	1	atualizar_usuario	\N	Atualizou o usuário: Stelio Bobo	127.0.0.1	\N	\N	\N	\N	\N	{"antes":{"id":5,"name":"Stelio Bobo","email":"e2@g","created_at":"2026-06-09T16:11:58.000000Z","updated_at":"2026-06-09T16:11:58.000000Z","role":"eleitor","categoria":"estudante","matricula":null,"curso":null,"departamento":null,"telefone":null,"ativo":true,"ultimo_acesso":null,"deleted_at":null,"preferencias":null,"foto":null},"depois":{"id":5,"name":"Stelio Bobo","email":"e22@g","created_at":"2026-06-09T16:11:58.000000Z","updated_at":"2026-06-09T16:19:22.000000Z","role":"eleitor","categoria":"estudante","matricula":null,"curso":null,"departamento":null,"telefone":null,"ativo":true,"ultimo_acesso":null,"deleted_at":null,"preferencias":null,"foto":null}}	info	sistema	2026-06-09 18:19:22	2026-06-09 18:19:22
\.


--
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.migrations (id, migration, batch) FROM stdin;
1	2014_10_12_000000_create_users_table	1
2	2014_10_12_100000_create_password_reset_tokens_table	1
3	2019_08_19_000000_create_failed_jobs_table	1
4	2019_12_14_000001_create_personal_access_tokens_table	1
5	2026_02_03_111542_add_fields_to_users_table	1
6	2026_02_03_111543_create_cargos_table	1
7	2026_02_03_111544_create_categorias_table	1
8	2026_02_03_111544_create_eleicoes_table	1
9	2026_02_03_111545_create_candidatos_table	1
10	2026_02_03_111545_create_votos_table	1
11	2026_02_03_111546_create_logs_table	1
12	2026_02_03_111546_create_resultados_table	1
13	2026_02_03_161115_make_modulo_nullable_in_logs_table	1
14	2026_02_03_231134_add_preferencias_to_users_table	1
15	2026_02_12_170014_remove_unique_status_from_eleicoes_table	1
\.


--
-- Data for Name: password_reset_tokens; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.password_reset_tokens (email, token, created_at) FROM stdin;
\.


--
-- Data for Name: personal_access_tokens; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.personal_access_tokens (id, tokenable_type, tokenable_id, name, token, abilities, last_used_at, expires_at, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: resultados; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.resultados (id, eleicao_id, candidato_id, tipo_resultado, total_votos, percentual, votos_validos, votos_nulos, votos_brancos, eleito, posicao, estatisticas, distribuicao_temporal, distribuicao_geografica, observacoes, created_at, updated_at, deleted_at) FROM stdin;
\.


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.users (id, name, email, password, created_at, updated_at, role, categoria, matricula, curso, departamento, telefone, ativo, ultimo_acesso, deleted_at, preferencias, foto) FROM stdin;
2	Comissão Eleitoral	c@g	$2y$12$9Xb3bncwme5aJ5QKumwuSOuHDEXcRG50xaRyMmhQsrMVjpj.jgqP6	2026-06-09 18:11:58	2026-06-09 18:11:58	comissao	docente	\N	\N	\N	\N	t	\N	\N	\N	\N
3	Maria Eleitora	e@g	$2y$12$OUVLXOW4SzhLe8t8csYtt.W.UdVHH6UaBFtwaJGFm7dtgin9HTchq	2026-06-09 18:11:58	2026-06-09 18:11:58	eleitor	estudante	\N	\N	\N	\N	t	\N	\N	\N	\N
4	Mayla Bobo	e1@g	$2y$12$Nk2UoL2Wf3BZQ9pCSa/Mzue1rBfaLygXG4VmOqnmOZE/wFP/A7qmy	2026-06-09 18:11:58	2026-06-09 18:11:58	eleitor	estudante	\N	\N	\N	\N	t	\N	\N	\N	\N
6	Marcia Nhantumbo	e3@g	$2y$12$93LLEU8YdWFCEdXE8jEURODLKhe10c245F7wzSKv9GjLyEIaQUB1u	2026-06-09 18:11:59	2026-06-09 18:11:59	eleitor	estudante	\N	\N	\N	\N	t	\N	\N	\N	\N
1	Administrador	a@g	$2y$12$Z7cxkB/JFCRDlu1hiQxvzujXy.UEiWPy/5z/U/gR/BTdi6bQEX4yG	2026-06-09 18:11:58	2026-06-09 18:15:23	admin	tecnico_administrativo	\N	\N	\N	\N	t	2026-06-09 18:15:23	\N	\N	\N
5	Stelio Bobo	e22@g	$2y$12$Z3KfS2uEZElaW3uC6HSAiupbyKfsYoHeR709La5iFzSmI4KNVzL7C	2026-06-09 18:11:58	2026-06-09 18:19:22	eleitor	estudante	\N	\N	\N	\N	t	\N	\N	\N	\N
\.


--
-- Data for Name: votos; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.votos (id, eleicao_id, candidato_id, user_id, hash_voto, tipo_voto, ip_address, user_agent, dispositivo, navegador, sistema_operacional, latitude, longitude, cidade, regiao, pais, valido, observacoes, created_at, updated_at) FROM stdin;
\.


--
-- Name: candidatos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.candidatos_id_seq', 1, false);


--
-- Name: cargos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.cargos_id_seq', 1, false);


--
-- Name: categorias_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.categorias_id_seq', 3, true);


--
-- Name: eleicoes_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.eleicoes_id_seq', 1, false);


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.failed_jobs_id_seq', 1, false);


--
-- Name: logs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.logs_id_seq', 3, true);


--
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.migrations_id_seq', 15, true);


--
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.personal_access_tokens_id_seq', 1, false);


--
-- Name: resultados_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.resultados_id_seq', 1, false);


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.users_id_seq', 6, true);


--
-- Name: votos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.votos_id_seq', 1, false);


--
-- Name: candidatos candidatos_numero_candidato_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.candidatos
    ADD CONSTRAINT candidatos_numero_candidato_unique UNIQUE (numero_candidato);


--
-- Name: candidatos candidatos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.candidatos
    ADD CONSTRAINT candidatos_pkey PRIMARY KEY (id);


--
-- Name: candidatos candidatos_user_id_eleicao_id_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.candidatos
    ADD CONSTRAINT candidatos_user_id_eleicao_id_unique UNIQUE (user_id, eleicao_id);


--
-- Name: cargos cargos_nome_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cargos
    ADD CONSTRAINT cargos_nome_unique UNIQUE (nome);


--
-- Name: cargos cargos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.cargos
    ADD CONSTRAINT cargos_pkey PRIMARY KEY (id);


--
-- Name: categorias categorias_nome_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.categorias
    ADD CONSTRAINT categorias_nome_unique UNIQUE (nome);


--
-- Name: categorias categorias_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.categorias
    ADD CONSTRAINT categorias_pkey PRIMARY KEY (id);


--
-- Name: categorias categorias_slug_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.categorias
    ADD CONSTRAINT categorias_slug_unique UNIQUE (slug);


--
-- Name: eleicoes eleicoes_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.eleicoes
    ADD CONSTRAINT eleicoes_pkey PRIMARY KEY (id);


--
-- Name: eleicoes eleicoes_slug_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.eleicoes
    ADD CONSTRAINT eleicoes_slug_unique UNIQUE (slug);


--
-- Name: failed_jobs failed_jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid);


--
-- Name: logs logs_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.logs
    ADD CONSTRAINT logs_pkey PRIMARY KEY (id);


--
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- Name: password_reset_tokens password_reset_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.password_reset_tokens
    ADD CONSTRAINT password_reset_tokens_pkey PRIMARY KEY (email);


--
-- Name: personal_access_tokens personal_access_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.personal_access_tokens
    ADD CONSTRAINT personal_access_tokens_pkey PRIMARY KEY (id);


--
-- Name: personal_access_tokens personal_access_tokens_token_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.personal_access_tokens
    ADD CONSTRAINT personal_access_tokens_token_unique UNIQUE (token);


--
-- Name: resultados resultados_eleicao_id_candidato_id_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.resultados
    ADD CONSTRAINT resultados_eleicao_id_candidato_id_unique UNIQUE (eleicao_id, candidato_id);


--
-- Name: resultados resultados_eleicao_id_tipo_resultado_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.resultados
    ADD CONSTRAINT resultados_eleicao_id_tipo_resultado_unique UNIQUE (eleicao_id, tipo_resultado);


--
-- Name: resultados resultados_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.resultados
    ADD CONSTRAINT resultados_pkey PRIMARY KEY (id);


--
-- Name: users users_email_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: votos votos_eleicao_id_user_id_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.votos
    ADD CONSTRAINT votos_eleicao_id_user_id_unique UNIQUE (eleicao_id, user_id);


--
-- Name: votos votos_hash_voto_unique; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.votos
    ADD CONSTRAINT votos_hash_voto_unique UNIQUE (hash_voto);


--
-- Name: votos votos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.votos
    ADD CONSTRAINT votos_pkey PRIMARY KEY (id);


--
-- Name: candidatos_aprovado_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX candidatos_aprovado_index ON public.candidatos USING btree (aprovado);


--
-- Name: candidatos_numero_candidato_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX candidatos_numero_candidato_index ON public.candidatos USING btree (numero_candidato);


--
-- Name: candidatos_votos_recebidos_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX candidatos_votos_recebidos_index ON public.candidatos USING btree (votos_recebidos);


--
-- Name: cargos_ativo_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX cargos_ativo_index ON public.cargos USING btree (ativo);


--
-- Name: cargos_categoria_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX cargos_categoria_index ON public.cargos USING btree (categoria);


--
-- Name: cargos_ordem_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX cargos_ordem_index ON public.cargos USING btree (ordem);


--
-- Name: categorias_ativo_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX categorias_ativo_index ON public.categorias USING btree (ativo);


--
-- Name: categorias_ordem_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX categorias_ordem_index ON public.categorias USING btree (ordem);


--
-- Name: categorias_slug_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX categorias_slug_index ON public.categorias USING btree (slug);


--
-- Name: eleicoes_cargo_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX eleicoes_cargo_id_index ON public.eleicoes USING btree (cargo_id);


--
-- Name: eleicoes_data_fim_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX eleicoes_data_fim_index ON public.eleicoes USING btree (data_fim);


--
-- Name: eleicoes_data_inicio_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX eleicoes_data_inicio_index ON public.eleicoes USING btree (data_inicio);


--
-- Name: eleicoes_resultado_publico_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX eleicoes_resultado_publico_index ON public.eleicoes USING btree (resultado_publico);


--
-- Name: eleicoes_slug_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX eleicoes_slug_index ON public.eleicoes USING btree (slug);


--
-- Name: eleicoes_status_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX eleicoes_status_index ON public.eleicoes USING btree (status);


--
-- Name: logs_acao_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX logs_acao_index ON public.logs USING btree (acao);


--
-- Name: logs_created_at_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX logs_created_at_index ON public.logs USING btree (created_at);


--
-- Name: logs_modulo_acao_created_at_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX logs_modulo_acao_created_at_index ON public.logs USING btree (modulo, acao, created_at);


--
-- Name: logs_modulo_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX logs_modulo_index ON public.logs USING btree (modulo);


--
-- Name: logs_severidade_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX logs_severidade_index ON public.logs USING btree (severidade);


--
-- Name: logs_tipo_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX logs_tipo_index ON public.logs USING btree (tipo);


--
-- Name: logs_tipo_severidade_created_at_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX logs_tipo_severidade_created_at_index ON public.logs USING btree (tipo, severidade, created_at);


--
-- Name: logs_user_id_created_at_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX logs_user_id_created_at_index ON public.logs USING btree (user_id, created_at);


--
-- Name: logs_user_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX logs_user_id_index ON public.logs USING btree (user_id);


--
-- Name: personal_access_tokens_tokenable_type_tokenable_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX personal_access_tokens_tokenable_type_tokenable_id_index ON public.personal_access_tokens USING btree (tokenable_type, tokenable_id);


--
-- Name: resultados_candidato_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX resultados_candidato_id_index ON public.resultados USING btree (candidato_id);


--
-- Name: resultados_eleicao_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX resultados_eleicao_id_index ON public.resultados USING btree (eleicao_id);


--
-- Name: resultados_eleicao_id_posicao_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX resultados_eleicao_id_posicao_index ON public.resultados USING btree (eleicao_id, posicao);


--
-- Name: resultados_eleito_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX resultados_eleito_index ON public.resultados USING btree (eleito);


--
-- Name: resultados_percentual_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX resultados_percentual_index ON public.resultados USING btree (percentual);


--
-- Name: resultados_posicao_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX resultados_posicao_index ON public.resultados USING btree (posicao);


--
-- Name: resultados_tipo_resultado_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX resultados_tipo_resultado_index ON public.resultados USING btree (tipo_resultado);


--
-- Name: users_ativo_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX users_ativo_index ON public.users USING btree (ativo);


--
-- Name: users_categoria_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX users_categoria_index ON public.users USING btree (categoria);


--
-- Name: users_matricula_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX users_matricula_index ON public.users USING btree (matricula);


--
-- Name: users_role_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX users_role_index ON public.users USING btree (role);


--
-- Name: votos_candidato_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX votos_candidato_id_index ON public.votos USING btree (candidato_id);


--
-- Name: votos_created_at_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX votos_created_at_index ON public.votos USING btree (created_at);


--
-- Name: votos_eleicao_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX votos_eleicao_id_index ON public.votos USING btree (eleicao_id);


--
-- Name: votos_eleicao_id_valido_created_at_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX votos_eleicao_id_valido_created_at_index ON public.votos USING btree (eleicao_id, valido, created_at);


--
-- Name: votos_hash_voto_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX votos_hash_voto_index ON public.votos USING btree (hash_voto);


--
-- Name: votos_tipo_voto_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX votos_tipo_voto_index ON public.votos USING btree (tipo_voto);


--
-- Name: votos_user_id_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX votos_user_id_index ON public.votos USING btree (user_id);


--
-- Name: votos_valido_index; Type: INDEX; Schema: public; Owner: postgres
--

CREATE INDEX votos_valido_index ON public.votos USING btree (valido);


--
-- Name: candidatos candidatos_cargo_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.candidatos
    ADD CONSTRAINT candidatos_cargo_id_foreign FOREIGN KEY (cargo_id) REFERENCES public.cargos(id) ON DELETE CASCADE;


--
-- Name: candidatos candidatos_eleicao_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.candidatos
    ADD CONSTRAINT candidatos_eleicao_id_foreign FOREIGN KEY (eleicao_id) REFERENCES public.eleicoes(id) ON DELETE CASCADE;


--
-- Name: candidatos candidatos_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.candidatos
    ADD CONSTRAINT candidatos_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: eleicoes eleicoes_cargo_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.eleicoes
    ADD CONSTRAINT eleicoes_cargo_id_foreign FOREIGN KEY (cargo_id) REFERENCES public.cargos(id) ON DELETE CASCADE;


--
-- Name: logs logs_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.logs
    ADD CONSTRAINT logs_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- Name: resultados resultados_candidato_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.resultados
    ADD CONSTRAINT resultados_candidato_id_foreign FOREIGN KEY (candidato_id) REFERENCES public.candidatos(id) ON DELETE CASCADE;


--
-- Name: resultados resultados_eleicao_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.resultados
    ADD CONSTRAINT resultados_eleicao_id_foreign FOREIGN KEY (eleicao_id) REFERENCES public.eleicoes(id) ON DELETE CASCADE;


--
-- Name: votos votos_candidato_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.votos
    ADD CONSTRAINT votos_candidato_id_foreign FOREIGN KEY (candidato_id) REFERENCES public.candidatos(id) ON DELETE CASCADE;


--
-- Name: votos votos_eleicao_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.votos
    ADD CONSTRAINT votos_eleicao_id_foreign FOREIGN KEY (eleicao_id) REFERENCES public.eleicoes(id) ON DELETE CASCADE;


--
-- Name: votos votos_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.votos
    ADD CONSTRAINT votos_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

\unrestrict VoLz7l59NdM1ryij1TjzG8RnI4IovwjpLhgGRazkNGBDRHY3OsygVMVAOJNRf3h

