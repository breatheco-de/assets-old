# get the state information
print("sleeping")
mambo.smart_sleep(2)
mambo.ask_for_state_update()
mambo.smart_sleep(2)

print("taking off!")
mambo.safe_takeoff(5)



print("landing")
mambo.safe_land(5)
mambo.smart_sleep(5)

print("disconnect")
mambo.disconnect()