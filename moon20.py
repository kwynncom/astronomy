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

print(json.dumps([t.utc_iso(), [almanac.MOON_PHASES[yi] for yi in y], y.tolist()]))

# 34.2402779,-84.1555425 - very close to top of Sawnee Mountain

cumming = api.wgs84.latlon(+34.2402779, -84.1555425)
f = almanac.risings_and_settings(eph, eph['Moon'], cumming)
t, y = almanac.find_discrete(t0, t1, f)

for ti, yi in zip(t, y):
    print(ti.utc_iso(), 'Rise' if yi else 'Set')

