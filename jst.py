from skyfield.api import load
from skyfield import almanac
import sys, json

ts = load.timescale()
eph = load('/opt/de421.bsp')

tsnow = ts.now()
t0    = tsnow - 10
t1    = tsnow + 10
t, y = almanac.find_discrete(t0, t1, almanac.moon_phases(eph))

tui = t.utc_iso()
# tl  = tui.tolist()

# print(json.dumps(tui))

al = [almanac.MOON_PHASES[yi] for yi in y]

yl = y.tolist()

res = [tui, al, yl]

print(json.dumps(res))

