wielkoscmapy = 800
zycie = 300
shortestG = 9999
dest = Vector4d(0, 0, 0, 0)

function reachedDestination(actorKnowledge, destination)
    pos =  actorKnowledge:getPosition()
    return pos:value(0) == destination:value(0) and pos:value(1) == destination:value(1)
end

function glitchinatorwhatTo( agent, actorKnowledge, time)
    if (not actorKnowledge:isMoving()) then
        dest = Vector4d(agent:randomDouble() * wielkoscmapy, agent:randomDouble() * wielkoscmapy, 0, 0)
    end

    if(reachedDestination(actorKnowledge, dest)) then
        dest = Vector4d(agent:randomDouble() * wielkoscmapy, agent:randomDouble() * wielkoscmapy, 0, 0)
    end

    if(actorKnowledge:getHealth()<zycie/2)then
        nav = actorKnowledge:getNavigation()
        for i=0, nav:getNumberOfTriggers() -1, 1 do
            trig = nav:getTrigger( i)
            if(trig:getType() == Trigger.Health and trig:isActive())then
                    dest = trig:getPosition()
            end
            if(trig:getType() == Trigger.Armour and trig:isActive())then
                    dest = trig:getPosition()
            end
        end
    end
    
    agent:moveTo(dest)

    if (actorKnowledge:getAmmo(Enumerations.RocketLuncher) ~= 0) then
        agent:selectWeapon(Enumerations.RocketLuncher);
    elseif (actorKnowledge:getAmmo(Enumerations.Railgun) ~= 0) then
            agent:selectWeapon(Enumerations.Railgun);
    elseif (actorKnowledge:getAmmo(Enumerations.Shotgun) ~= 0) then
            agent:selectWeapon(Enumerations.Shotgun);
    elseif (actorKnowledge:getAmmo(Enumerations.Chaingun) ~= 0) then
            agent:selectWeapon(Enumerations.Chaingun);
    end

    if(actorKnowledge:getAmmo(Enumerations.RocketLuncher)==0 and actorKnowledge:getAmmo(Enumerations.Railgun) == 0 and actorKnowledge:getAmmo(Enumerations.Shotgun) == 0 and actorKnowledge:getAmmo(Enumerations.Chaingun) == 0)then
        nav = actorKnowledge:getNavigation()
        for i=0, nav:getNumberOfTriggers() -1, 1 do
            trig = nav:getTrigger( i)
            dist = trig:getPosition() - actorKnowledge:getPosition()
            if(dist:length()<shortestG and trig:isActive())then
                shortestG = i
            end
        end
        agent:moveTo(nav:getTrigger(shortestG):getPosition())
    end

    enemies = actorKnowledge:getSeenFoes()
    if ( enemies:size() > 0) then
        agent:shootAtPoint( enemies:at(0):getPosition())
    end
end;

function glitchinatoronStart( agent, actorKnowledge, time)
    io.write( "My name is: ", actorKnowledge:getName(), "\n")
    dest = Vector4d(agent:randomDouble() * wielkoscmapy, agent:randomDouble() * wielkoscmapy, 0, 0)
end
