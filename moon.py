from skyfield.api import load
from skyfield import almanac
import sys

ts = load.timescale()
eph = load('/opt/de421.bsp')

tsnow = ts.now()
t0    = tsnow - int(sys.argv[1])
t1    = tsnow + int(sys.argv[2])
t, y = almanac.find_discrete(t0, t1, almanac.moon_phases(eph))

print(t.utc_iso())
print([almanac.MOON_PHASES[yi] for yi in y])
print(y)
