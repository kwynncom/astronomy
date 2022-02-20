from skyfield import api      # api needed for wgs84
from skyfield.api import load
from skyfield import almanac
import sys, json

ts = load.timescale()
eph = load('/opt/de421.bsp')

tsnow = ts.now()
t0    = tsnow - int(sys.argv[1])
t1    = tsnow + int(sys.argv[2])
t, y = almanac.find_discrete(t0, t1, almanac.moon_phases(eph))

# rise and set

sawnee = api.wgs84.latlon(+34.2402779, -84.1555425)
f = almanac.risings_and_settings(eph, eph['Moon'], sawnee)
tr, yr = almanac.find_discrete(t0, t1, f)

print(json.dumps([t.utc_iso(), [almanac.MOON_PHASES[yi] for yi in y], y.tolist(),
    tr.utc_iso(), yr.tolist() ]))
