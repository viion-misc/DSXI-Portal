-----------------------------------------------
{{ PORTAL_NOTICE }}
-----------------------------------------------
-----------------------------------------------
-------------   GLOBAL SETTINGS   -------------
-----------------------------------------------

-- This is to allow server operators to further customize their servers.  As more features are added to pXI, the list will surely expand.
-- Anything scripted can be customized with proper script editing.

-- PLEASE REQUIRE THIS SCRIPT IN ANY SCRIPTS YOU DO: ADD THIS LINE TO THE TOP!!!!
-- require("scripts/globals/settings");
-- With this script added to yours, you can pull variables from it!!

-- Always include status.lua, which defines mods
-- require("scripts/globals/status");

-- Common functions
require("scripts/globals/common");

-- Enable Extension (1= yes 0= no)
ENABLE_COP     = {{ ENABLE_COP }};
ENABLE_TOAU    = {{ ENABLE_TOAU }};
ENABLE_WOTG    = {{ ENABLE_WOTG }};
ENABLE_ACP     = {{ ENABLE_ACP }};
ENABLE_AMK     = {{ ENABLE_AMK }};
ENABLE_ASA     = {{ ENABLE_ASA }};
ENABLE_ABYSSEA = {{ ENABLE_ABYSSEA }};
ENABLE_SOA     = {{ ENABLE_SOA }};
ENABLE_ROV     = {{ ENABLE_ROV }};

-- Setting to lock content more accurately to the expansions you have defined above
-- This generally results in a more accurate presentation of your selected expansions
-- as well as a less confusing player experience for things that are disabled (things that are disabled are not loaded)
-- This feature correlates to the required_expansion column in the SQL files
RESTRICT_BY_EXPANSION = {{ RESTRICT_BY_EXPANSION }};

-- CHARACTER CONFIG
INITIAL_LEVEL_CAP = {{ INITIAL_LEVEL_CAP }}; -- The initial level cap for new players.  There seems to be a hardcap of 255.
MAX_LEVEL = {{ MAX_LEVEL }}; -- Level max of the server, lowers the attainable cap by disabling Limit Break quests.
NORMAL_MOB_MAX_LEVEL_RANGE_MIN = {{ NORMAL_MOB_MAX_LEVEL_RANGE_MIN }}; -- Lower Bound of Max Level Range for Normal Mobs (0 = Uncapped)
NORMAL_MOB_MAX_LEVEL_RANGE_MAX = {{ NORMAL_MOB_MAX_LEVEL_RANGE_MAX }}; -- Upper Bound of Max Level Range for Normal Mobs (0 = Uncapped)
START_GIL = {{ START_GIL }}; --Amount of gil given to newly created characters.
START_INVENTORY = {{ START_INVENTORY }}; -- Starting inventory and satchel size.  Ignores values < 30.  Do not set above 80!
OPENING_CUTSCENE_ENABLE = {{ OPENING_CUTSCENE_ENABLE }}; --Set to 1 to enable opening cutscenes, 0 to disable.
SUBJOB_QUEST_LEVEL = {{ SUBJOB_QUEST_LEVEL }}; -- Minimum level to accept either subjob quest.  Set to 0 to start the game with subjobs unlocked.
ADVANCED_JOB_LEVEL = {{ ADVANCED_JOB_LEVEL }}; -- Minimum level to accept advanced job quests.  Set to 0 to start the game with advanced jobs.
ALL_MAPS = {{ ALL_MAPS }}; -- Set to 1 to give starting characters all the maps.
UNLOCK_OUTPOST_WARPS = {{ UNLOCK_OUTPOST_WARPS }}; -- Set to 1 to give starting characters all outpost warps.  2 to add Tu'Lia and Tavnazia.

SHOP_PRICE      = {{ SHOP_PRICE }}; -- Multiplies prices in NPC shops.
GIL_RATE        = {{ GIL_RATE }}; -- Multiplies gil earned from quests.  Won't always display in game.
EXP_RATE        = {{ FOV_EXP_RATE }}; -- Multiplies exp earned from fov.
TABS_RATE       = {{ FOV_TABS_RATE }}; -- Multiplies tabs earned from fov.
CURE_POWER      = {{ CURE_POWER }}; -- Multiplies amount healed from Healing Magic, including the relevant Blue Magic.
ELEMENTAL_POWER = {{ ELEMENTAL_POWER }}; -- Multiplies damage dealt by Elemental and non-drain Dark Magic.
DIVINE_POWER    = {{ DIVINE_POWER }}; -- Multiplies damage dealt by Divine Magic.
NINJUTSU_POWER  = {{ NINJUTSU_POWER }}; -- Multiplies damage dealt by Ninjutsu Magic.
BLUE_POWER      = {{ BLUE_POWER }}; -- Multiplies damage dealt by Blue Magic.
DARK_POWER      = {{ DARK_POWER }}; -- Multiplies amount drained by Dark Magic.
ITEM_POWER      = {{ ITEM_POWER }}; -- Multiplies the effect of items such as Potions and Ethers.
WEAPON_SKILL_POWER  = {{ WEAPON_SKILL_POWER }}; -- Multiplies damage dealt by Weapon Skills.
WEAPON_SKILL_POINTS = {{ WEAPON_SKILL_POINTS }}; -- Multiplies points earned during weapon unlocking.
USE_ADOULIN_WEAPON_SKILL_CHANGES = {{ USE_ADOULIN_WEAPON_SKILL_CHANGES }}; -- true/false. Change to toggle new Adoulin weapon skill damage calculations

HARVESTING_BREAK_CHANCE = {{ HARVESTING_BREAK_CHANCE }}; -- % chance for the sickle to break during harvesting.  Set between 0 and 1.
EXCAVATION_BREAK_CHANCE = {{ EXCAVATION_BREAK_CHANCE }}; -- % chance for the pickaxe to break during excavation.  Set between 0 and 1.
LOGGING_BREAK_CHANCE    = {{ LOGGING_BREAK_CHANCE }}; -- % chance for the hatchet to break during logging.  Set between 0 and 1.
MINING_BREAK_CHANCE     = {{ MINING_BREAK_CHANCE }}; -- % chance for the pickaxe to break during mining.  Set between 0 and 100.
HARVESTING_RATE         = {{ HARVESTING_RATE }}; -- % chance to recieve an item from haresting.  Set between 0 and 1.
EXCAVATION_RATE         = {{ EXCAVATION_RATE }}; -- % chance to recieve an item from excavation.  Set between 0 and 1.
LOGGING_RATE            = {{ LOGGING_RATE }}; -- % chance to recieve an item from logging.  Set between 0 and 1.
MINING_RATE             = {{ MINING_RATE }}; -- % chance to recieve an item from mining.  Set between 0 and 100.

-- SE implemented coffer/chest illusion time in order to prevent coffer farming. No-one in the same area can open a chest or coffer for loot (gil, gems & items)
-- till a random time between MIN_ILLSION_TIME and MAX_ILLUSION_TIME. During this time players can loot keyitem and item related to quests (AF, maps... etc.)
COFFER_MAX_ILLUSION_TIME = {{ COFFER_MAX_ILLUSION_TIME }};  -- 1 hour
COFFER_MIN_ILLUSION_TIME = {{ COFFER_MIN_ILLUSION_TIME }};  -- 30 minutes
CHEST_MAX_ILLUSION_TIME  = {{ CHEST_MAX_ILLUSION_TIME }};  -- 1 hour
CHEST_MIN_ILLUSION_TIME  = {{ CHEST_MIN_ILLUSION_TIME }};  -- 30 minutes

-- Sets spawn type for: Behemoth, Fafnir, Adamantoise, King Behemoth, Nidhog, Aspidochelone.
-- Use 0 for timed spawns, 1 for force pop only, 2 for both
LandKingSystem_NQ = {{ LandKingSystem_NQ }};
LandKingSystem_HQ = {{ LandKingSystem_HQ }};

-- DYNAMIS SETTINGS
    BETWEEN_2DYNA_WAIT_TIME = {{ BETWEEN_2DYNA_WAIT_TIME }};        -- wait time between 2 Dynamis (in real day) min: 1 day
        DYNA_MIDNIGHT_RESET = {{ DYNA_MIDNIGHT_RESET }};     -- if true, makes the wait time count by number of server midnights instead of full 24 hour intervals
             DYNA_LEVEL_MIN = {{ DYNA_LEVEL_MIN }};       -- level min for entering in Dynamis
    TIMELESS_HOURGLASS_COST = {{ TIMELESS_HOURGLASS_COST }};   -- cost of the timeless hourglass for Dynamis.
     CURRENCY_EXCHANGE_RATE = {{ CURRENCY_EXCHANGE_RATE }};      -- X Tier 1 ancient currency -> 1 Tier 2, and so on.  Certain values may conflict with shop items.  Not designed to exceed 198.
RELIC_2ND_UPGRADE_WAIT_TIME = {{ RELIC_2ND_UPGRADE_WAIT_TIME }};      -- wait time for 2nd relic upgrade (stage 2 -> stage 3) in seconds. 604800s = 1 RL week.
RELIC_3RD_UPGRADE_WAIT_TIME = {{ RELIC_3RD_UPGRADE_WAIT_TIME }};      -- wait time for 3rd relic upgrade (stage 3 -> stage 4) in seconds. 295200s = 82 hours.
FREE_COP_DYNAMIS = {{ FREE_COP_DYNAMIS }}; -- Authorize player to entering inside COP Dynamis without completing COP mission ( 1 = enable 0= disable)

-- QUEST/MISSION SPECIFIC SETTINGS
WSNM_LEVEL = {{ WSNM_LEVEL }}; -- Min Level to get WSNM Quests
WSNM_SKILL_LEVEL = {{ WSNM_SKILL_LEVEL }};
AF1_QUEST_LEVEL = {{ AF1_QUEST_LEVEL }}; -- Minimum level to start AF1 quest
AF2_QUEST_LEVEL = {{ AF2_QUEST_LEVEL }}; -- Minimum level to start AF2 quest
AF3_QUEST_LEVEL = {{ AF3_QUEST_LEVEL }}; -- Minimum level to start AF3 quest
AF1_FAME = {{ AF1_FAME }}; -- base fame for completing an AF1 quest
AF2_FAME = {{ AF2_FAME }}; -- base fame for completing an AF2 quest
AF3_FAME = {{ AF3_FAME }}; -- base fame for completing an AF3 quest
DEBUG_MODE = {{ DEBUG_MODE }}; -- Set to 1 to activate auto-warping to the next location (only supported by certain missions / quests).
QM_RESET_TIME = {{ QM_RESET_TIME }}; -- Default time (in seconds) you have from killing ???-pop mission NMs to click again and get key item, until ??? resets.
OldSchoolG1 = {{ OldSchoolG1 }}; -- Set to true to require farming Exoray Mold, Bombd Coal, and Ancient Papyrus drops instead of allowing key item method.
OldSchoolG2 = {{ OldSchoolG2 }}; -- Set true to require the NMs for "Atop the Highest Mountains" be dead to get KI like before SE changed it.
FrigiciteDuration = {{ FrigiciteDuration }}; -- When OldSChoolG2 is enabled, this is the time (in seconds) you have from killing Boreal NMs to click the "???" target.

-- FIELDS OF VALOR/Grounds of Valor SETTINGS
REGIME_WAIT = {{ REGIME_WAIT }}; --Make people wait till 00:00 game time as in retail. If it's 0, there is no wait time.
FIELD_MANUALS = {{ FIELD_MANUALS }}; -- Enables Fields of Valor manuals
LOW_LEVEL_REGIME = {{ LOW_LEVEL_REGIME }}; --Allow people to kill regime targets even if they give no exp, allowing people to farm regime targets at 75 in low level areas.
GROUNDS_TOMES = {{ GROUNDS_TOMES }}; -- Enables Grounds of Valor tomes

-- JOB ABILITY/TRAIT SPECIFIC SETTINGS
CIRCLE_KILLER_EFFECT = {{ CIRCLE_KILLER_EFFECT }}; -- Intimidation percentage granted by circle effects. (made up number)
KILLER_EFFECT = {{ KILLER_EFFECT }}; -- Intimidation percentage from killer job traits.

-- SPELL SPECIFIC SETTINGS
DIA_OVERWRITE = {{ DIA_OVERWRITE }}; --Set to 1 to allow Bio to overwrite same tier Dia.  Default is 1.
BIO_OVERWRITE = {{ BIO_OVERWRITE }}; --Set to 1 to allow Dia to overwrite same tier Bio.  Default is 0.
BARELEMENT_OVERWRITE = {{ BARELEMENT_OVERWRITE }}; --Set to 1 to allow Barelement spells to overwrite each other (prevent stacking).  Default is 1.
BARSTATUS_OVERWRITE = {{ BARSTATUS_OVERWRITE }}; --Set to 1 to allow Barstatus spells to overwrite each other (prevent stacking).  Default is 1.
STONESKIN_CAP = {{ STONESKIN_CAP }}; -- soft cap for hp absorbed by stoneskin
BLINK_SHADOWS = {{ BLINK_SHADOWS }};   -- number of shadows supplied by Blink spell
ENSPELL_DURATION = {{ ENSPELL_DURATION }}; -- duration of RDM en-spells
SPIKE_EFFECT_DURATION = {{ SPIKE_EFFECT_DURATION }}; -- the duration of RDM, BLM spikes effects (not Reprisal)
ELEMENTAL_DEBUFF_DURATION = {{ ELEMENTAL_DEBUFF_DURATION }}; -- base duration of elemental debuffs
AQUAVEIL_COUNTER = {{ AQUAVEIL_COUNTER }};  -- Base amount of hits Aquaveil absorbs to prevent spell interrupts. Retail is 1.
ABSORB_SPELL_AMOUNT = {{ ABSORB_SPELL_AMOUNT }}; -- how much of a stat gets absorbed by DRK absorb spells - expected to be a multiple of 8.
ABSORB_SPELL_TICK = {{ ABSORB_SPELL_TICK }}; -- duration of 1 absorb spell tick
SNEAK_INVIS_DURATION_MULTIPLIER = {{ SNEAK_INVIS_DURATION_MULTIPLIER }}; -- multiplies duration of sneak,invis,deodorize to reduce player torture. 1 = retail behavior.
USE_OLD_CURE_FORMULA = {{ USE_OLD_CURE_FORMULA }}; -- true/false. if true, uses older cure formula (3*MND + VIT + 3*(healing skill/5)) // cure 6 will use the newer formula

-- CELEBRATIONS
EXPLORER_MOOGLE = {{ EXPLORER_MOOGLE }}; -- Enables Explorer Moogle teleports
EXPLORER_MOOGLE_LEVELCAP = {{ EXPLORER_MOOGLE_LEVELCAP }};
JINX_MODE_2005 = {{ JINX_MODE_2005 }}; -- Set to 1 to give starting characters swimsuits from 2005.  Ex: Hume Top
JINX_MODE_2008 = {{ JINX_MODE_2008 }}; -- Set to 1 to give starting characters swimsuits from 2008.  Ex: Custom Top
JINX_MODE_2012 = {{ JINX_MODE_2012 }}; -- Set to 1 to give starting characters swimsuits from 2012.  Ex: Marine Top
SUMMERFEST_2004 = {{ SUMMERFEST_2004 }}; -- Set to 1 to give starting characters Far East dress from 2004.  Ex: Onoko Yukata
SUNBREEZE_2009 = {{ SUNBREEZE_2009 }}; -- Set to 1 to give starting characters Far East dress from 2009.  Ex: Otokogusa Yukata
SUNBREEZE_2011 = {{ SUNBREEZE_2011 }}; -- Set to 1 to give starting characters Far East dress from 2011.  Ex: Hikogami Yukata
CHRISTMAS = {{ CHRISTMAS }}; -- Set to 1 to give starting characters Christmas dress.
HALLOWEEN = {{ HALLOWEEN }}; -- Set to 1 to give starting characters Halloween items (Does not start event).
HALLOWEEN_2005 = {{ HALLOWEEN_2005 }}; -- Set to 1 to Enable the 2005 version of Harvest Festival, will start on Oct. 20 and end Nov. 1.
HALLOWEEN_YEAR_ROUND = {{ HALLOWEEN_YEAR_ROUND }}; -- Set to 1 to have Harvest Festival initialize outside of normal times.

-- MISC
HOMEPOINT_HEAL = {{ HOMEPOINT_HEAL }}; --Set to 1 if you want Home Points to heal you like in single-player Final Fantasy games.
RIVERNE_PORTERS = {{ RIVERNE_PORTERS }}; -- Time in seconds that Unstable Displacements in Cape Riverne stay open after trading a scale.
LANTERNS_STAY_LIT = {{ LANTERNS_STAY_LIT }}; -- time in seconds that lanterns in the Den of Rancor stay lit.
ENABLE_COP_ZONE_CAP = {{ ENABLE_COP_ZONE_CAP }}; -- enable or disable lvl cap
TIMEZONE_OFFSET = {{ TIMEZONE_OFFSET }}; -- Offset from UTC used to determine when "JP Midnight" is for the server.  Default is JST (+9.0).
ALLOW_MULTIPLE_EXP_RINGS = {{ ALLOW_MULTIPLE_EXP_RINGS }}; -- Set to 1 to remove ownership restrictions on the Chariot/Empress/Emperor Band trio.
BYPASS_EXP_RING_ONE_PER_WEEK = {{ BYPASS_EXP_RING_ONE_PER_WEEK }}; -- -- Set to 1 to bypass the limit of one ring per Conquest Tally Week.
NUMBER_OF_DM_EARRINGS = {{ NUMBER_OF_DM_EARRINGS }}; -- Number of earrings players can simultaneously own from Divine Might before scripts start blocking them (Default: 1)
HOMEPOINT_TELEPORT = {{ HOMEPOINT_TELEPORT }}; -- Enables the homepoint teleport system
DIG_ABUNDANCE_BONUS = {{ DIG_ABUNDANCE_BONUS }}; -- Increase chance of digging up an item (450  = item digup chance +45)
DIG_FATIGUE = {{ DIG_FATIGUE }}; -- Set to 0 to disable Dig Fatigue
MIASMA_FILTER_COOLDOWN = {{ MIASMA_FILTER_COOLDOWN }};  -- Number of days a player can obtain a Miasma Filter KI for any of the Boneyard Gully ENMs (Minimum:1)
FORCE_SPAWN_QM_RESET_TIME = {{ FORCE_SPAWN_QM_RESET_TIME }}; -- Number of seconds the ??? remains hidden for after the despawning of the mob it force spawns.

-- LIMBUS
BETWEEN_2COSMOCLEANSE_WAIT_TIME = {{ BETWEEN_2COSMOCLEANSE_WAIT_TIME }}; -- day between 2 limbus keyitem  (default 3 days)
DIMENSIONAL_PORTAL_UNLOCK = {{ DIMENSIONAL_PORTAL_UNLOCK }}; -- Set true to bypass requirements for using dimensional portals to reach sea for Limbus

-- ABYSSEA
VISITANT_BONUS = {{ VISITANT_BONUS }}; -- Default: 1.00 - (retail) - Multiplies the base time value of each Traverser Stone.
