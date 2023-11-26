bodyguardDestBG = Vector4d(0, 0, 0, 0)
bossBG = null
shortestBG = 9999

function lookOnBossBG(agent, actorKnowledge)
    friends = actorKnowledge:getSeenFriends()
    if (friends:size() > 0) then
        for i = 0, friends:size() - 1, 1 do
            if (friends:at(i):getName() == "glitchinator") then
                bossBG = friends:at(i)
            end
        end
        bodyguardDestBG = bossBG:getPosition() + Vector4d(10, 10, 0, 0)
        agent:moveTo(bodyguardDestBG)
    end

end

function buggyguardwhatTo(agent, actorKnowledge, time)
    lookOnBossBG(agent, actorKnowledge)

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
            if(dist:length()<shortestBG and trig:isActive())then
                shortestBG = i
            end
        end
        agent:moveTo(nav:getTrigger(shortestBG):getPosition())
    end

    enemies = actorKnowledge:getSeenFoes()
    if ( enemies:size() > 0) then
        agent:shootAtPoint( enemies:at(0):getPosition())
    end
end

function buggyguardonStart(agent, actorKnowledge, time)
    io.write("My name is: ", actorKnowledge:getName(), "\n")
    lookOnBossBG(agent, actorKnowledge)
end